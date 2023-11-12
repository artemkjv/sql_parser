<?php

namespace App\Http\Controllers;

use App\Enums\FileType;
use App\Factories\ParserJobFactory;
use App\Http\Requests\Dump\ExportRequest;
use App\Http\Requests\Dump\StoreRequest;
use App\Jobs\ParseSqlDump;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Dump\DumpRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DumpController extends Controller
{

    public function __construct(
        private readonly DumpRepositoryInterface    $dumpRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
    )
    {
    }

    public function create() {
        return Inertia::render('Dump/Create');
    }

    public function store(StoreRequest $request) {
        $files = $request->file('files');
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $sql = $file->storeAs('temp', Str::random() . '.sql');
            ParseSqlDump::dispatch(storage_path("/app/$sql"), $filename, \request()->user()->id);
        }
        return back()->with('message', 'Dump parsing has been initiated!');
    }

    public function index() {
        $dumps = $this->dumpRepository->paginateByUser(\request()->user());
        $fileTypes = FileType::cases();
        return inertia('Dump/Index', compact('dumps', 'fileTypes'));
    }

    public function export(ExportRequest $request) {
        $payload = $request->validated();
        $articles = $this->articleRepository->getByUserAndDumpIds(\request()->user(), $payload['id']);
        $typeValue = $payload['type'];
        $fileType = FileType::from($typeValue);
        $dumpIdsStr = implode('.', $payload['id']);
        $directory = storage_path("app/public/$dumpIdsStr/");
        try {
            \Illuminate\Support\Facades\File::makeDirectory($directory, recursive: true);
        } catch (\Throwable $e) {}
        $filePath = $directory.$fileType->name.'.'.$fileType->value;
        $job = ParserJobFactory::make($fileType, $articles, $filePath);
        dispatch($job);
        $filePath = "/storage/".str_replace(storage_path('app/public/'), '', $filePath);
        return \inertia('Dump/Export', compact('filePath'));
    }

    public function show(int $id) {
        $dump = $this->dumpRepository->getByUserAndId(\request()->user(), $id);
        return inertia('Dump/Show', compact('dump'));
    }

    public function destroy(int $id) {
        try {
            $this->dumpRepository->deleteByUserAndId(\request()->user(), $id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors([
                'dump' => 'Dump not found!'
            ]);
        }
        return back()->with('message', 'Dump deleted successfully!');
    }

}
