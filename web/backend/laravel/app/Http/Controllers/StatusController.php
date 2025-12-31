class StatusController extends Controller {
    public function index() {
        $status = shell_exec("netes-engine --status");
        return view('index', compact('status'));
    }
}

