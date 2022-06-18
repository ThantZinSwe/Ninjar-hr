<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class SalaryController extends Controller {

    //Salary page
    public function index() {

        if ( !auth()->user()->can( 'salary_view' ) ) {
            abort( 404 );
        }

        return view( 'salary.index' );
    }

    //Salary DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'salary_view' ) ) {
            abort( 404 );
        }

        $salaries = Salary::query();

        return Datatables::of( $salaries )
            ->addColumn( 'employee_name', function ( $each ) {
                return $each->employee ? $each->employee->name : '-';
            } )
            ->filterColumn( 'employee_name', function ( $query, $keyword ) {
                $query->whereHas( 'employee', function ( $q1 ) use ( $keyword ) {
                    $q1->where( 'name', 'like', '%' . $keyword . '%' );
                } );
            } )
            ->editColumn( 'month', function ( $each ) {
                return Carbon::parse( '2021-' . $each->month . '-01' )->format( 'M' );
            } )
            ->editColumn( 'amount', function ( $each ) {
                return number_format( $each->amount );
            } )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'salary_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'salary.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'salary_delete' ) ) {
                    $delete_icon = '<a href ="#" class="delete-btn" data-id="' . $each->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                }

                return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
            } )
            ->addColumn( 'plus', function ( $each ) {
                return null;
            } )
            ->editColumn( 'updated_at', function ( $each ) {
                return Carbon::parse( $each->updated_at )->format( 'Y-m-d H:i:s' );
            } )
            ->rawColumns( array( 'action' ) )
            ->make( true );
    }

    //Salary Create Page
    public function create() {

        if ( !auth()->user()->can( 'salary_create' ) ) {
            abort( 404 );
        }

        $employee = User::get();

        return view( 'salary.create', compact( 'employee' ) );
    }

    //Salary Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'salary_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employee' => 'required',
            'month'    => 'required',
            'year'     => 'required',
            'amount'   => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $salaries = $this->salaryData( $request );

        Salary::create( $salaries );
        return redirect()->route( 'salary.index' )->with( array( 'create' => 'Salary crate success...' ) );

    }

    //Salary Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'salary_edit' ) ) {
            abort( 404 );
        }

        $salaries = Salary::findOrFail( $id );
        $employee = User::get();

        return view( 'salary.edit' )->with( array( 'salary' => $salaries, 'employee' => $employee ) );
    }

    //Salary Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'salary_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employee' => 'required',
            'month'    => 'required',
            'year'     => 'required',
            'amount'   => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $updateData = $this->salaryData( $request );

        Salary::findOrFail( $id )->update( $updateData );
        return redirect()->route( 'salary.index' )->with( array( 'update' => 'Salaries data update success..' ) );

    }

    //Salary Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'salary_delete' ) ) {
            abort( 404 );
        }

        Salary::findOrFail( $id )->delete();

        return 'success';
    }

    //Request Salary Data
    private function salaryData( Request $request ) {
        return array(
            'user_id' => $request->employee,
            'month'   => $request->month,
            'year'    => $request->year,
            'amount'  => $request->amount,
        );
    }

}
