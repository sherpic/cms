<?php namespace Soda\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Soda\Models\Page;
use Soda\Models\Template;
use Soda\Models\PageType;
use Soda\Facades\Soda;


class PageController extends Controller
{

    use    Traits\TreeableTrait;
    public $hint = 'page';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        //$this->middleware('auth');
        $this->model = $page;
        $this->tree = $page;
    }


    /**
     * Show the page.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {

        if (!isset($id) || !$id || $id == '#') {
            $page = $this->model->getRoots()->first();    //todo: from application.
        } elseif ($id) {
            $page = $this->model->where('id', $id)->first();
        } elseif ($request->input('id')) {
            $page = $this->model->where('id', $request->input('id'))->first();
        }

        $page_types = PageType::get();

        $tree = $this->htmlTree($request, $page->id, $this->hint);


        $pages = $page->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree();

        return view('soda::page.index', ['hint'=>$this->hint, 'pages' => $pages, 'tree' => $tree, 'page_types' => $page_types]);
    }

    public function view($id)
    {
        if ($id) {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->findOrFail($id);
        } else {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->getRoots()->first();
        }
        if (@$page->type->identifier) {
            $page_table = Soda::dynamicModel('soda_' . $page->type->identifier,
                $model->type->fields->lists('field_name')->toArray())->where('page_id', $page->id)->first();
        } else {
            $page_table = null;
        }


        return view('soda::page.view', ['hint'=>$this->hint, 'model' => $model, 'page_table' => $page_table]);
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $page = $this->model->findOrFail($id);
        } else {
            $page = new Page();
        }
        $page->fill($request->all());
        $page->save();


        //we also need to save the settings - careful here..
        $page->load('type.fields');

        $dyn_table = Soda::dynamicModel('soda_' . $page->type->identifier,
            $page->type->fields->lists('field_name')->toArray())->where('page_id', $page->id)->first();

        $settings = $request->input('settings');
        $dyn_table->forceFill($settings);
        $dyn_table->save();
        //$dyn_table->fill()

        return redirect()->route('soda.'.$this->hint . '.view', ['id' => $request->id])->with('success', 'page updated');
    }

    public function getMakeRoot($id)
    {
        $this->model->find($id)->makeRoot(0);
    }

    /**
     * Main page view method.
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function page($slug)
    {

        if (starts_with('/', $slug)) {
            $page = Page::where('slug', $slug)->first(); //TODO: might not really be page::
        } else {
            $page = Page::where('slug', '/' . $slug)->first();
        }


        if (!$page) {
            abort(404);
        }

        return (\Soda\Components\Page::constructView($page, ['page' => $page]));
    }

    public function createForm(Request $request, $parent_id = null)
    {

        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
        } else {
            $parent = $this->model->getRoots()->first();
        }

        $this->model->parent_id = $parent->id;
        $this->model->page_type_id = $request->input('page_type_id');

        return view('soda::page.view', ['model'=>$this->model, 'hint'=>$this->hint]);
    }

    /**
     * create page save functions
     * @param null $parent_id
     */
    public function create(Request $request, $parent_id = null)
    {
        $page = $this->model;
        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
        } else {
            $parent = $this->model->getRoots()->first();
        }

        $page->name = $request->input('name');
        $page->slug = $parent->generateSlug($request->input('slug'));
        $page->status_id = 1;
        $page->action_type = 'view';
        $page->package = 'soda';
        $page->action = 'default.view'; //TODO: allow for inheriting these properties.
        $page->application_id = Soda::getApplication()->id;

        $parent->addChild($page);

        $page->save();
        dd('saved.');
    }

}
