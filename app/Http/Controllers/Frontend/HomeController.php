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
use IntranetMkt\Models\BookAccount;
use IntranetMkt\Models\CostCenter;
use IntranetMkt\Models\Cycle;
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $date = date('Ym');

        $cycle_code = $request->get('cycle_code', null, true);

        $cycle_current = Cycle::where('code','>',$date)->firstOrFail();

        $cycle_code = (is_null($cycle_code))?$cycle_current->code:$cycle_code;

        $user = Auth::user();
        $total_amount   =  number_format(DB::table('expenses')->where('user_id','=',$user->id)->sum('total_amount'), 2, '.', ',');
        $total_expenses =  DB::table('expenses')->where('user_id','=',$user->id)->count('id');



        $reports = null;
        $report_cost_center = null;
        $budget_reports = null;
        $report_role = array();

        switch($user->role_id)
        {
            case 1: //SUPERVISOR
                $reports = DB::select("
select
d.name as division,ba.code as cod_cuenta,ba.name as cuenta,u.name as usuario,c.code as ciclo,
0 as presupuesto,
sum(e.total_amount) as gastado
from expenses as e
inner join expense_types as et on et.id = e.expense_type_id
inner join book_accounts as ba on ba.id = et.book_account_id
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join division_user as du on du.user_id = u.id
inner join divisions as d on d.id = du.division_id
where u.id = ".$user->id." and ba.active = 1  and e.approval_1 = 1 and c.code = ".$cycle_code."
group by d.name, ba.code, ba.name,u.name,c.code");


                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '$cycle_code' and e.user_id  = ".$user->id."
group by d.name,
c.code,
u.name
order by u.name asc;");


                break;
            case 2: //JEFE DE PRODUCTO

                $report_cost_center_total = DB::select("
                select d.name as division, cc.code as cod_centro,
cc.name as centro,  sum(b.amount) as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
inner join divisions as d on d.id = b.division_id
where b.user_id = ".$user->id."  and b.amount > 0.5 and  cy.code = '".$cycle_code."' and ba.active = '1'
group by d.name,cc.code,cc.name
order by d.name,cc.name;
");

                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '".$cycle_code."' and e.id in (
select y.expense_id from expense_amounts as y where y.cost_center_id in (
select x.cost_center_id from budgets as x where x.cycle_id = ".$cycle_current->id." and x.user_id = ".$user->id.")
)
group by d.name,
c.code,
u.name
order by r.name asc;");

                $report_cost_center = DB::select('select cy.code as ciclo,ba.name as cuenta, cc.code as cod_centro,
cc.name as centro,  b.amount as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join expense_types as et on et.id = e.expense_type_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id and et.book_account_id = ba.id ) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
where b.user_id = '.$user->id.'  and b.amount > 0.5 and ba.active = 1 and cy.code = '.$cycle_code.'
order by cy.code, ba.name,cc.name;');

                //Reporte Cuenta vs presupuesto
                $reports = DB::select('select
d.name as division,
ba.code as cod_cuenta,
ba.name as cuenta,
c.code as ciclo,
r.name as usuario,
sum(amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
inner join expense_types as et on et.id = ex.expense_type_id
where ex.cycle_id = c.id and ex.division_id = d.id and et.book_account_id = ba.id) as gastado
from budgets as bu
inner join divisions as d on d.id = bu.division_id
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
inner join users u on u.id = bu.user_id
inner join roles as r on r.id = u.role_id
where bu.user_id = '.$user->id.' and ba.active = 1  and c.code = '.$cycle_code.'
group by
d.name,
ba.code,
ba.name,
c.code,
r.name
order by u.name,ba.id asc;');

                $budget_reports = DB::select('select
d.name as division,
c.month as mes,
sum(bu.amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
where ex.cycle_id = c.id and ex.division_id = d.id ) as gastado
from budgets as bu
inner join divisions as d on d.id = bu.division_id
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
inner join users u on u.id = bu.user_id
where u.id = '.$user->id.' and ba.active = 1
group by
d.name,
c.month
order by c.id asc;');
                break;
            case 3: //ASISTENTE DE MARKETING
                $reports = DB::select('
select
d.name as division,ba.code as cod_cuenta,ba.name as cuenta,u.name as usuario,c.code as ciclo,
sum(e.total_amount) as gastado,
0 as presupuesto
from expenses as e
inner join expense_types as et on et.id = e.expense_type_id
inner join book_accounts as ba on ba.id = et.book_account_id
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join division_user as du on du.user_id = u.id
inner join divisions as d on d.id = du.division_id
where u.id = '.$user->id.' and ba.active = 1  and e.approval_1 = 1  and c.code = '.$cycle_code.'
group by d.name, ba.code, ba.name,u.name,c.code');

                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '".$cycle_code."' and e.user_id  = ".$user->id."
group by d.name,
c.code,
u.name
order by u.name asc;");

                break;
            case 4:   //GERENTE DIVISION

                $report_cost_center_total = DB::select("
                select d.name as division, cc.code as cod_centro,
cc.name as centro,  sum(b.amount) as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id
and e.approval_1 = 1 and e.approval_2 = 1
) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
inner join divisions as d on d.id = b.division_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")
and b.amount > 0.5 and  cy.code = '".$cycle_code."' and ba.active = '1'
group by d.name,cc.code,cc.name
order by cc.name;
");


                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '".$cycle_code."'
and  e.division_id in (select division_id from division_user where user_id = ".$user->id.")
and e.approval_1 = 1 and e.approval_2 = 1
group by d.name,
c.code,
u.name
order by r.name asc;");

                $reports = DB::select("select
d.name as division,
ba.code as cod_cuenta,
ba.name as cuenta,
c.code as ciclo,
'Empresa' as usuario,
sum(amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
inner join expense_types as et on et.id = ex.expense_type_id
where ex.cycle_id = c.id and ex.division_id = d.id and et.book_account_id = ba.id and ex.approval_1 = 1 and ex.approval_2 = 1) as gastado
from budgets as bu
inner join divisions as d on d.id = bu.division_id
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
where d.id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1
 and c.code = '".$cycle_code."'
group by
d.name,
ba.code,
ba.name,
c.code
order by ba.id asc;");

                $budget_reports = DB::select("select
'Empresa' as division,
c.month as mes,
sum(bu.amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
where ex.cycle_id = c.id and ex.approval_1 = 1 and ex.approval_2 = 1) as gastado
from budgets as bu
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
inner join users u on u.id = bu.user_id
where bu.division_id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1
group by
c.month
order by c.id asc;");


                $report_cost_center = DB::select("select cy.code as ciclo,
ba.name as cuenta,
cc.code as cod_centro,
cc.name as centro,  b.amount as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join expense_types as et on et.id = e.expense_type_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id and et.book_account_id = ba.id and  e.approval_1 = 1 and e.approval_2 = 1) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")   and b.amount > 0.5 and ba.active = 1 and cy.code = '".$cycle_code."'
order by cy.code, ba.name,cc.name;");
                break;

            case 5: // CONTROL DE GESTION


                $report_cost_center_total = DB::select("
                select d.name as division, cc.code as cod_centro,
cc.name as centro,  sum(b.amount) as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id
and e.approval_1 = 1 and e.approval_2 = 1  and e.approval_3 = 1
) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
inner join divisions as d on d.id = b.division_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")
and b.amount > 0.5 and  cy.code = '".$cycle_code."' and ba.active = '1'
group by d.name, cc.code,cc.name
order by d.name, cc.name;
");

                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '".$cycle_code."'
and  e.division_id in (select division_id from division_user where user_id = ".$user->id.")
and e.approval_1 = 1 and e.approval_2 = 1 and e.approval_3 = 1
group by d.name,
c.code,
u.name
order by r.name asc;");


                $reports = DB::select("select
d.name as division,
ba.code as cod_cuenta,
ba.name as cuenta,
c.code as ciclo,
'Empresa' as usuario,
sum(amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
inner join expense_types as et on et.id = ex.expense_type_id
where ex.cycle_id = c.id and ex.division_id = d.id and et.book_account_id = ba.id and ex.approval_1 = 1 and ex.approval_2 = 1 and ex.approval_3 = 1) as gastado
from budgets as bu
inner join divisions as d on d.id = bu.division_id
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
where d.id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1
 and c.code = '".$cycle_code."'
group by
d.name,
ba.code,
ba.name,
c.code
order by ba.id asc;");

                $budget_reports = DB::select("select
'Empresa' as division,
sum(bu.amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
where ex.cycle_id = c.id and ex.approval_1 = 1 and ex.approval_2 = 1) as gastado
from budgets as bu
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
inner join users u on u.id = bu.user_id
where bu.division_id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1

group by
c.month
order by c.id asc;");


                $report_cost_center = DB::select("select cy.code as ciclo,
ba.name as cuenta,
cc.code as cod_centro,
cc.name as centro,  b.amount as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join expense_types as et on et.id = e.expense_type_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id and et.book_account_id = ba.id and  e.approval_1 = 1 and e.approval_2 = 1  and e.approval_3 = 1) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")   and b.amount > 0.5 and ba.active = 1 and cy.code = '".$cycle_code."'
order by cy.code, ba.name,cc.name;");
                break;

            case 6:


                $report_cost_center_total = DB::select("
                select d.name as division, cc.code as cod_centro,
cc.name as centro,  sum(b.amount) as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id
and e.approval_1 = 1 and e.approval_2 = 1  and e.approval_3 = 1 and e.approval_4 = 1
) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
inner join divisions as d on d.id = b.division_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")
and b.amount > 0.5 and  cy.code = '".$cycle_code."' and ba.active = '1'
group by d.name, cc.code,cc.name
order by d.name, cc.name;
");

                $report_role = DB::select("select
d.name as division,
c.code as ciclo,
u.name as rol,
sum(e.total_amount) as gastado
from expenses as e
inner join users as u on u.id = e.user_id
inner join cycles as c on c.id = e.cycle_id
inner join roles as r on r.id = u.role_id
inner join expense_types as et on et.id = e.expense_type_id
inner join divisions as d on d.id = e.division_id
where  c.code = '".$cycle_code."'
and  e.division_id in (select division_id from division_user where user_id = ".$user->id.")
and e.approval_1 = 1 and e.approval_2 = 1 and e.approval_3 = 1
group by d.name,
c.code,
u.name
order by r.name asc;");

                $reports = DB::select("select
d.name as division,
ba.code as cod_cuenta,
ba.name as cuenta,
c.code as ciclo,
'Empresa' as usuario,
sum(amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
inner join expense_types as et on et.id = ex.expense_type_id
where ex.cycle_id = c.id and ex.division_id = d.id and et.book_account_id = ba.id
and ex.approval_1 = 1 and ex.approval_2 = 1  and ex.approval_3 = 1 and ex.approval_4 = 1) as gastado
from budgets as bu
inner join divisions as d on d.id = bu.division_id
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
where d.id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1
 and c.code = '".$cycle_code."'
group by
d.name,
ba.code,
ba.name,
c.code
order by ba.id asc;");

                $budget_reports = DB::select("select
'Empresa' as division,
sum(amount) as presupuesto,
(select sum(ex.total_amount) from expenses as ex
where ex.cycle_id = c.id and ex.approval_1 = 1 and  ex.approval_2 = 1 and ex.approval_3 = 1 and ex.approval_4 = 1) as gastado
from budgets as bu
inner join cost_centers as cc on cc.id = bu.cost_center_id
inner join book_accounts as ba on ba.id = bu.book_account_id
inner join cycles as c on c.id = bu.cycle_id
inner join users u on u.id = bu.user_id
where bu.division_id in (select division_id from division_user where user_id = ".$user->id.") and ba.active = 1
group by
c.month
order by c.id asc;");

                $report_cost_center = DB::select("select cy.code as ciclo,
ba.name as cuenta,
cc.code as cod_centro,
cc.name as centro,  b.amount as presupuesto,
(select
sum(e.total_amount*percent/100) as new_amount
from expense_amounts as ea
inner join expenses as e on e.id = ea.expense_id
inner join expense_types as et on et.id = e.expense_type_id
inner join cost_centers as ccc on ccc.id = ea.cost_center_id
where ccc.id = cc.id and e.cycle_id = cy.id and et.book_account_id = ba.id and  e.approval_1 = 1 and e.approval_2 = 1  and e.approval_3 = 1  and e.approval_4 = 1) as gastado
from budgets as b
inner join cost_centers as cc on cc.id = b.cost_center_id
inner join cycles as cy on cy.id = b.cycle_id
inner join book_accounts as ba on ba.id = b.book_account_id
where b.division_id in (select division_id from division_user where user_id = ".$user->id.")   and b.amount > 0.5 and ba.active = 1 and cy.code = '".$cycle_code."'
order by cy.code, ba.name,cc.name;");

                break;
        }

        $book_accounts = BookAccount::where('active','=',true)->get();


        $cycles = Cycle::where('code','>=',$date)->take(12)->get();

        //select sum(total_amount) from expenses where user_id = 66;
        return view('frontend.home', compact('total_amount', 'user','total_expenses','reports','book_accounts','cycles',
            'budget_reports','report_cost_center','cycle_code','report_role','report_cost_center_total'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function gastos(Request $request)
    {
        $expense_types = ExpenseType::all();

        $date = date('Ym');

        $cycle_code = $request->get('cycle_code', null, true);

        $cycle_current = Cycle::where('code','>',$date)->firstOrFail();

        $cycle_code = (is_null($cycle_code))?$cycle_current->code:$cycle_code;



        $cycles = Cycle::where('code','>=',$date)->take(12)->get();

        $cycle_actual = Cycle::where('code','=',$cycle_code)->firstOrFail();

        $user = Auth::user();
        return view('frontend.gastos', compact('expense_types', 'user','cycles','cycle_code','cycle_current','cycle_actual'));
    }

    /**
     * @param $expense_id
     * @param $cost_center_id
     * @return Response
     */
    public function agregar_centro_costo($expense_id, $cost_center_id)
    {

        $expensesAmounts = ExpenseAmount::where('expense_id','=',$expense_id)
                            ->where('cost_center_id','=', $cost_center_id);

        $expensesAmounts->delete();

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
                $detail->porcentaje = $percent;
                $detail->amount = $percent*$detail->expense->total_amount/100;
                $detail->save();

            }else{
                $detail->porcentaje =100-$acomulado;
                $detail->amount = $detail->porcentaje*$detail->expense->total_amount/100;
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
                $detail->porcentaje = $percent;
                $detail->amount = $percent*$detail->expense->total_amount/100;
                $detail->save();

            }else{
                $detail->porcentaje =100-$acomulado;
                $detail->amount = $detail->porcentaje*$detail->expense->total_amount/100;
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

        $date = date('Ym', strtotime('+1 month'));
        $cycles = Cycle::where('code','>=',$date)->take(12)->get();
        return view('frontend.nuevo_gasto', compact('expense_types','user','cycles'));
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
            ->orderBy('buy_orders.id','desc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->file, function($file) use($expense, $data){

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
            ->orderBy('entertainments.id','asc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->file, function($file) use($expense, $data){

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
            ->orderBy('medical_campaigns.id','asc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->file, function($file) use($expense, $data){

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
            ->orderBy('request_attentions.id','asc')
            ->get();

        $data = json_decode(json_encode((array) $routes), true);

        Excel::load('/storage/app/'.$file_format->file, function($file) use($expense, $data){

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