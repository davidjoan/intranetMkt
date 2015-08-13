<?php namespace IntranetMkt\Http\Controllers\Frontend;
/**
 * Created by PhpStorm.
 * User: David
 * Date: 4/06/15
 * Time: 17:00
 */
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;
use IntranetMkt\Models\CostCenter;
use IntranetMkt\Models\Expense;
use IntranetMkt\Models\ExpenseAmount;
use IntranetMkt\Models\ExpenseDetail;
use IntranetMkt\Models\ExpenseType;
use IntranetMkt\Models\FileFormat;
use IntranetMkt\User;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use PDF;
use Excel;
use DB;
use Session;

class HomeController extends Controller{


    private $responseFactory;

    /**
     * Create a new controller instance.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware('auth');
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $total_amount   =  number_format(DB::table('expenses')->where('user_id','=',$user->id)->sum('total_amount'), 2, '.', ',');
        $total_expenses =  DB::table('expenses')->where('user_id','=',$user->id)->count('id');

        $reports = DB::select('select
MONTH(e.application_date) as mes,
r.name as rol,  u.name as usuario, ba.code as cuenta, et.name as tipo,
sum(estimated_amount) as estimado, 0 as presupuesto, 0 as diferencia from expenses as e
inner join expense_types as et on et.id = e.expense_type_id
inner join book_accounts as ba on ba.id = et.book_account_id
inner join users as u on u.id = e.user_id
inner join roles as r on r.id = u.role_id
group by MONTH(e.application_date), ba.code, et.name,u.name,r.name
order by MONTH(e.application_date),ba.code, et.name, u.name;');


        //select sum(total_amount) from expenses where user_id = 66;
        return view('frontend.home', compact('total_amount', 'user','total_expenses','reports'));
    }

    public function gastos()
    {
        $expense_types = ExpenseType::all();

        $user = Auth::user();
        return view('frontend.gastos', compact('expense_types', 'user'));
    }

    /**
     * @param $expense_id
     * @param $cost_center_id
     * @return Response
     */
    public function agregar_centro_costo($expense_id, $cost_center_id)
    {
        $expense_amount = new ExpenseAmount();
        $expense_amount->expense_id = $expense_id;
        $expense_amount->cost_center_id = $cost_center_id;

        $expense_amount->save();

        $expensesAmounts = ExpenseAmount::where('expense_id','=',$expense_id)->get();

        $cantidad = $expensesAmounts->count();

        $acomulado = 0;

        foreach($expensesAmounts as $key => $detail){
            if($key < ($cantidad-1)){
                $percent = sprintf('%0.2f',100/$cantidad);
                $acomulado = $acomulado+$percent;
                $detail->percent = $percent;
                $detail->save();

            }else{
                $detail->percent =100-$acomulado;
                $detail->save();
            }
        }

        return $this->responseFactory->json('ok');

    }

    /**
     * @param $expense_amount_id
     * @return Response
     */
    public function eliminar_centro_costo($expense_amount_id)
    {
        $expense_amount = ExpenseAmount::find($expense_amount_id);
        $expense_id = $expense_amount->expense_id;
        $expense_amount->delete();

        $expensesAmounts = ExpenseAmount::where('expense_id','=',$expense_id)->get();

        $cantidad = $expensesAmounts->count();
        $acomulado = 0;


        foreach($expensesAmounts as $key => $detail){
            if($key < ($cantidad-1)){
                $percent = sprintf('%0.2f',100/$cantidad);
                $acomulado = $acomulado+$percent;
                $detail->percent = $percent;
                $detail->save();

            }else{
                $detail->percent =100-$acomulado;
                $detail->save();
            }
        }

        return $this->responseFactory->json('ok');

    }


    /**
     * @param $expense_code
     * @return \Illuminate\View\View
     */
    public function aprobar($expense_code)
    {
        $status = 'ok';
        $user = Auth::user();

        $role_id = $user->role_id;
        $expense = Expense::where('code','=',$expense_code)->first();


        if(in_array($role_id, array(1,2,3)))   //Supervisores, Managers y Asistentes
        {
            $expense->approval_1 = 1;

        }elseif(in_array($role_id, array(4))){ //Gerente de Division
            if($expense->approval_1 == 1){
                $expense->approval_2 = 1;
            }else{
                $status = 'fail';
            }


        }elseif(in_array($role_id, array(5))){ //Control de Gestion
            if($expense->approval_2 == 1){
                $expense->approval_3 = 1;
            }else{
                $status = 'fail';
            }

        }elseif(in_array($role_id, array(6))){ // Gerente General
            if($expense->approval_3 == 1){
                $expense->approval_4 = 1;
            }else{
                $status = 'fail';
            }
        }

        $expense->save();

        return $this->responseFactory->json($status);
    }

    /**
     * @param $expense_code
     * @return \Illuminate\View\View
     */
    public function desaprobar($expense_code)
    {
        $status = 'ok';
        $user = Auth::user();


        $role_id = $user->role_id;
        $expense = Expense::where('code','=',$expense_code)->first();


        if(in_array($role_id, array(1,2,3)))   //Supervisores, Managers y Asistentes
        {
            if($expense->approval_2 == 0 && $expense->approval_3 == 0 && $expense->approval_4 == 0){
                $expense->approval_1 = 0;
            } else{
                $status = 'fail';
            }

        }elseif(in_array($role_id, array(4))){ //Gerente de Division
            if($expense->approval_3 == 0 && $expense->approval_4 == 0){
                $expense->approval_2 = 0;
            } else{
                $status = 'fail';
            }


        }elseif(in_array($role_id, array(5))){ //Control de Gestion
            if($expense->approval_4 == 0){
                $expense->approval_3 = 0;
            } else{
                $status = 'fail';
            }
        }elseif(in_array($role_id, array(6))){ // Gerente General
            $expense->approval_4 = 0;
        }

        $expense->save();

        return $this->responseFactory->json($status);
    }


    /**
     * @param Request $request
     * @param $division_id
     * @return Response
     */
    public function cost_center(Request $request, $division_id){

        $query_in = $request->get('query',null,true);

        $cost_centers = DB::table('cost_centers')->where('division_id','=',$division_id)
            ->where('cost_center_type','=','Producto');

        if(!(is_null($query_in) || $query_in == '')){

            $cost_centers->where('name', 'LIKE','%'.strtoupper($query_in).'%')
                ->orWhere(function($query) use ($query_in)
                {
                    $query->where('code','LIKE','%'.strtoupper($query_in).'%');
                });

        }

        return $this->responseFactory->json($cost_centers->get());
    }

    public function cost_center_by_expense($expense_id){

        $cost_centers = Expense::find($expense_id)->cost_centers();

        return $this->responseFactory->json($cost_centers);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload(Request $request){

        $expense_id     = $request->get('expense_id',null,true);
        $file_format_id = $request->get('file_format_id',null,true);
        $file           = $request->file('file');

        if(is_file($file)){
            DB::table('expense_details')->where('expense_id', '=', $expense_id)->where('file_format_id','=',$file_format_id)->delete();

            $expenseDetail = new ExpenseDetail();
            $expenseDetail->expense_id = $expense_id;
            $expenseDetail->file_format_id = $file_format_id;

            $extension = $file->getClientOriginalExtension();
            //Storage::disk('local')->put($expense_id.'_'.$file_format_id.'.'.$extension,  File::get($file));
            $file->move(public_path().'/uploads/expense_details', $expense_id.'-'.$file_format_id.'.'.$extension);

            $expenseDetail->mime = $file->getClientMimeType();
            $expenseDetail->original_filename = $file->getClientOriginalName();
            $expenseDetail->filename = $expense_id.'-'.$file_format_id.'.'.$extension;

            $expenseDetail->save();

            Session::flash('message', 'Se guardo correctamente el Formato');

        }else{
            Session::flash('message', 'Tiene que adjuntar el archivo');

        }

        return redirect('frontend/detalle/'.$expense_id);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function nuevo_gasto()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $expense_types = ExpenseType::all();
        return view('frontend.nuevo_gasto', compact('expense_types','user'));
    }

    public function presupuestos()
    {
        return view('frontend.presupuestos');
    }


    /**
     * Display a detail of expense.
     * @param  $id
     *
     * @return Response
     */
    public function detalle($id)
    {
        $expense  = Expense::find($id);
        return view('frontend.detalle', compact('expense'));
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     *
     * @return Response
     */
    public function portada($expense_id){

        $expense  = Expense::find($expense_id);

        $pdf = PDF::loadView('frontend.pdf.folder', array('expense' => $expense));
        return $pdf->download('portada_'.$expense->code.'.pdf');
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     *
     * @return Response
     */
    public function export($expense_id){

        $expense  = Expense::find($expense_id);

        $pdf = PDF::loadView('frontend.pdf.format1', array('expense' => $expense));
        $pdf->setOrientation('landscape');
        return $pdf->download('formato_'.$expense->code.'.pdf');
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     * @param int $file_format_id
     *
     * @return Response
     */
    public function export_xls($expense_id, $file_format_id){

        $expense      = Expense::find($expense_id);
        $file_format  = FileFormat::find($file_format_id);

        $routes = DB::table('buy_orders')
            ->join('expenses','expenses.id','=','buy_orders.expense_id')
            ->join('users','expenses.user_id','=','users.id')
            ->select(
                'buy_orders.code',
                'buy_orders.cost_center',
                'buy_orders.book_account',
                'buy_orders.active',
                'buy_orders.expenditure',
                'buy_orders.inventory',
                'buy_orders.quantity',
                'buy_orders.price_unit',
                'buy_orders.description',
                'buy_orders.estimated_value',
                'buy_orders.destination',
                'buy_orders.delivery_date'
            )
            ->where('buy_orders.expense_id','=',$expense_id)
            ->orderBy('buy_orders.created_at','desc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->code.'.xls', function($file) use($expense, $data){

            $file->setActiveSheetIndex(0)->setCellValue('D8', $expense->user->name);
            $file->setActiveSheetIndex(0)->setCellValue('L8', $expense->application_date);
            $file->setActiveSheetIndex(0)->setCellValue('P8', $expense->code);
            $file->setActiveSheetIndex(0)->setCellValue('D45', $expense->description);

            $row = 13;
            foreach($data as $key => $temp) {
                $col = 1;
                foreach(array_keys($temp) as $value) {
                    $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $temp[$value]);
                    $col++;
                }
                $row++;
            }
        })->export('xls');
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     * @param int $file_format_id
     *
     * @return Response
     */
    public function export_entertainment_xls($expense_id, $file_format_id){



        $expense  = Expense::find($expense_id);
        $file_format  = FileFormat::find($file_format_id);

        $routes = DB::table('entertainments')
            ->join('expenses','expenses.id','=','entertainments.expense_id')
            ->join('users','expenses.user_id','=','users.id')
            ->select(
                'users.name as supervisor',
                'entertainments.consultor as consultor',
                'entertainments.entertainment_type as tipo',
                'entertainments.delivery_date as fecha',
                'entertainments.place as lugar',
                'entertainments.qty_doctors as HCP',
                'entertainments.estimated_value as monto'
            )
            ->where('entertainments.expense_id','=',$expense_id)
            ->orderBy('entertainments.created_at','desc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->code.'.xls', function($file) use($expense, $data){

            $file->setActiveSheetIndex(0)->setCellValue('C4', $expense->expense_type->name);

            $row = 8; // 1-based index
            foreach($data as $key => $temp) {
                $col = 1;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key+1);
                $col++;
                foreach(array_keys($temp) as $value) {
                    $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $temp[$value]);
                    $col++;

                }
                $col+2;

                $cost_unit = $temp['monto']*1.0/$temp['HCP'];
                $max_value = null;

                switch($temp['tipo'])
                {
                    case 'Refrigerio':
                        $max_value= 25;break;
                    case 'Desayuno':
                        $max_value= 40;break;
                    case 'Almuerzo':
                        $max_value= 100;break;
                    case 'Cena':
                        $max_value= 150;break;
                    default :
                        $max_value= 150;break;
                }
                $col++;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, round($cost_unit,2));
                $col++;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $max_value);
                $col++;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ($cost_unit > $max_value)?'Exceso':'OK');
                $row++;

            }




        })->export('xls');
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     * @param int $file_format_id
     *
     * @return Response
     */
    public function export_campaign_xls($expense_id, $file_format_id){
        $expense  = Expense::find($expense_id);
        $file_format  = FileFormat::find($file_format_id);

        $routes = DB::table('medical_campaigns')
            ->join('expenses','expenses.id','=','medical_campaigns.expense_id')
            ->join('expense_types','expenses.expense_type_id','=','expense_types.id')
            ->join('users','expenses.user_id','=','users.id')
            ->select(
                'users.name as supervisor',
                'medical_campaigns.consultor as consultor',
                'expense_types.name as tipo',
                'medical_campaigns.delivery_date as fecha',
                'medical_campaigns.place as lugar',
                'medical_campaigns.cmp',
                'medical_campaigns.doctor',
                'medical_campaigns.estimated_value as monto'
            )
            ->where('medical_campaigns.expense_id','=',$expense_id)
            ->orderBy('medical_campaigns.created_at','desc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->code.'.xls', function($file) use($expense, $data){

            $file->setActiveSheetIndex(0)->setCellValue('C4', $expense->expense_type->name);

            $row = 8; // 1-based index
            foreach($data as $key => $temp) {
                $col = 1;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key+1);
                $col++;
                foreach(array_keys($temp) as $value) {
                    $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $temp[$value]);
                    $col++;
                }
                $row++;
            }
        })->export('xls');
    }

    /**
     * Display a detail of expense.
     * @param  int $expense_id
     * @param int $file_format_id
     *
     * @return Response
     */
    public function export_attention_xls($expense_id, $file_format_id){
        $expense  = Expense::find($expense_id);
        $file_format  = FileFormat::find($file_format_id);

        $routes = DB::table('request_attentions')
            ->join('expenses','expenses.id','=','request_attentions.expense_id')
            ->join('expense_types','expenses.expense_type_id','=','expense_types.id')
            ->join('users','expenses.user_id','=','users.id')
            ->select(
                'users.name as supervisor',
                'request_attentions.promotora',
                'expense_types.name as tipo',
                'request_attentions.client_code',
                'request_attentions.client',
                'request_attentions.description',
                'request_attentions.price_unit',
                'request_attentions.quantity',
                'request_attentions.estimated_value',
                'request_attentions.reason'
            )
            ->where('request_attentions.expense_id','=',$expense_id)
            ->orderBy('request_attentions.created_at','desc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->code.'.xls', function($file) use($expense, $data){

            $file->setActiveSheetIndex(0)->setCellValue('C4', $expense->expense_type->name);

            $row = 8; // 1-based index
            foreach($data as $key => $temp) {
                $col = 1;
                $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key+1);
                $col++;
                foreach(array_keys($temp) as $value) {
                    $file->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $temp[$value]);
                    $col++;
                }
                $row++;
            }
        })->export('xls');
    }


    /**
     * Display a detail of expense.
     * @param  int $expense_id
     *
     * @return Response
     */
    public function report($expense_id){

        $expense  = Expense::find($expense_id);
        return view('frontend.pdf.format1', compact('expense'));
    }
}