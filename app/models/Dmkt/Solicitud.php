<?php

namespace Dmkt;
use \Eloquent;
use \Auth;

class Solicitud extends Eloquent
{
    protected $table = TB_SOLICITUD;
    protected $primaryKey = 'id';

    protected function getFecOriginAttribute()
    {
        return $this->attributes['created_at'];
    }

    protected function getCreatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d H:i');
    }

    public function lastId()
    {
        $lastId = Solicitud::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected static function solInst( $periodo )
    {
        return Solicitud::orderBy('id','desc')->whereHas('detalle' , function ( $q ) use ( $periodo )
        {
            $q->whereHas( TB_PERIODO , function ( $t ) use ( $periodo )
            {
                $t->where( 'aniomes' , $periodo );
            });
        })->whereNotIn( 'id_estado' , array( CANCELADO , RECHAZADO ) )->get();
    }

    public function state()
    {
        return $this->hasOne( 'Common\State' , 'id' , 'id_estado' );
    }

    public function assignedTo()
    {
        return $this->belongsTo( 'User' , 'id_user_assign' );
    }

    public function personalTo()
    {
        return $this->hasOne( 'Users\Personal' , 'user_id' , 'id_user_assign' );
    }

    public function toAcceptedApprovedHistories()
    {
        return $this->hasMany( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->whereIn( 'status_to' , array( ACEPTADO , APROBADO ) );
    }

    protected function expenseHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , GASTO_HABILITADO );
    }

    protected function registerHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , REGISTRADO );
    }

    protected function toDeliveredHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' )->where( 'status_to' , ENTREGADO );
    }

    protected function toPendingHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , PENDIENTE );
    }

    protected function toDevolutionHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , DEVOLUCION );
    }

    public function approvedHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , APROBADO );
    }

    public function toDepositHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' )->where( 'status_to' , DEPOSITO_HABILITADO );
    }

    public function toAdvanceSeatHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , DEPOSITADO );
    }

    public function toGenerateHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , GENERADO );
    }

    public function orderHistories()
    {
        return $this->hasMany( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->orderBy( 'created_at' , 'ASC' );
    }        

    public function acceptHist()
    {
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where( 'status_to' , ACEPTADO )->orderBy( 'updated_at' , 'DESC' );
    }

    public function fromUserHistory( $userFrom )
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'user_from' , $userFrom )->first();
    }

    public function histories(){
        return $this->hasMany('System\SolicitudHistory','id_solicitud');
    }

    public function lastHistory(){
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' )->orderBy( 'updated_at' , 'desc' );
    }

    public function lastApprovedHistory(){
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' )->whereIn( 'status_to' , [ ACEPTADO , APROBADO ] )->orderBy( 'updated_at' , 'desc' );
    }

    public function detalle()
    {
        return $this->belongsTo( 'Dmkt\SolicitudDetalle' , 'id_detalle' );
    }

    protected function typeSolicitude()
    {
        return $this->hasOne( 'Dmkt\SolicitudType' , 'id' , 'idtiposolicitud' );
    }

    public function products()
    {
        return $this->hasMany( 'Dmkt\SolicitudProduct' , 'id_solicitud' , 'id' );
    }

    public function clients(){
        return $this->hasMany( 'Dmkt\SolicitudClient' , 'id_solicitud' , 'id' )->orderBy( 'id' , 'ASC' );
    }

    public function createdBy()
    {
        return $this->belongsTo( 'User' , 'created_by' );
    }

    public function createdPersonal()
    {
        return $this->hasOne( 'Users\Personal' , 'user_id' , 'created_by' );
    }

    public function updatedBy()
    {
        return $this->belongsTo( 'User' , 'updated_by' );
    }

    public function gerente()
    {
        return $this->hasMany( 'Dmkt\SolicitudGer' , 'id_solicitud' );
    }

    public function managerEdit( $userType )
    {
        return $this->hasMany( 'Dmkt\SolicitudGer' , 'id_solicitud'  , 'id' )->where( 'permiso' , 1 )->where( 'tipo_usuario' , $userType );
    }

    public function activity()
    {
        return $this->hasOne('Dmkt\Activity','id','id_actividad');
    }

    public function activityTrash()
    {
        return $this->hasOne( 'Dmkt\Activity' , 'id' , 'id_actividad' )->withTrashed();
    }

    public function expenses()
    {
        return $this->hasMany( 'Expense\Expense' , 'id_solicitud' , 'id' )->orderBy( 'updated_at' , 'desc');
    }

    protected function lastExpense()
    {
        return $this->hasOne( 'Expense\Expense' , 'id_solicitud' , 'id' )->orderBy( 'updated_at' , 'desc' );
    }

    protected function advanceCreditEntry()
    {
        return $this->hasOne( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_BASE )->where( 'tipo_asiento' , 'A' );        
    }

    protected function advanceDepositEntry()
    {
        return $this->hasOne( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_DEPOSITO )->where( 'tipo_asiento' , 'A' );        
    }

    protected function dailyEntries()
    {
        return $this->hasMany( 'Expense\Entry' , 'id_solicitud' )->where( 'tipo_asiento' , ASIENTO_GASTO_TIPO )->orderBy( 'id' , 'ASC' );
    }
    
    protected function investment()
    {
        return $this->hasOne( 'Dmkt\InvestmentType' , 'id' , 'id_inversion' );
    }

    public function orderProducts()
    {
        return $this->hasMany( 'Dmkt\SolicitudProduct' , 'id_solicitud' )->orderBy( 'updated_at' , 'DESC' );
    }

    public function devolutions()
    {
        return $this->hasMany( 'Devolution\Devolution' , 'id_solicitud' );
    }

    protected static function getUserSolicituds()
    {
        return \DB::table( 'LISTADO_SOLICITUD' )
            //->where( 'rownum' , '<=' , 1000 )
            //->whereIn( 'estado_id' , [ 1  ] )
            //->where( 'responsable_id' , Auth::user()->id )
            ->get();
    }

}