<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Schools\School;
use App\Http\Requests\Schools\SchoolUpdate;
use App\Repositories\Academics\SchooolRepository;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $schoolRepo;
    public function __construct(SchooolRepository $schoolRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->schoolRepo = $schoolRepo;
    }


    public function index()
    {
        $schools['schools'] = $this->schoolRepo->getAll();
        return view('pages.schools.index',$schools);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(School $req)
    {
        //$data = $req->only(['name', 'description']);
        $data = $req->only(['name','description']);
        $this->schoolRepo->create($data);

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $school['school'] = $school = $this->schoolRepo->find($id);

        return !is_null($school ) ? view('pages.schools.edit', $school)
            : Qs::goWithDanger('schools.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolUpdate $req, $id)
    {

        //$data = $req->only(['name', 'description']);
        $data = $req->only(['name']);
        $this->schoolRepo->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->schoolRepo->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
