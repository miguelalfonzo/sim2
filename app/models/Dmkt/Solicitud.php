<?php

namespace Dmkt;

use \Eloquent;

class Solicitud extends Eloquent
{
    protected $table = 'SOLICITUD';
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
            $q->whereHas('periodo' , function ( $t ) use ( $periodo )
            {
                $t->where( 'aniomes' , $periodo );
            });
        })->where( 'id_estado' , '<>' , CANCELADO )->get();
    }

    public function state()
    {
        return $this->hasOne( 'Common\State' , 'id' , 'id_estado' );
    }

    public function asignedTo()
    {
        return $this->hasOne('User','id','id_user_assign');
    }

    protected function toAcceptedApprovedHistories()
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

    protected function toPendingHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , PENDIENTE );
    }

    public function approvedHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , APROBADO );
    }

    public function toDepositHistory()
    {
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , DEPOSITO_HABILITADO );
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

    public function rejectedHist(){
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where( 'status_to' , RECHAZADO )->orWhere( 'status_to' , CANCELADO );
    }

    public function histories(){
        return $this->hasMany('System\SolicitudHistory','id_solicitud');
    }

    public function lastHistory(){
        return $this->hasOne( 'System\SolicitudHistory' , 'id_solicitud' )->orderBy( 'updated_at' , 'desc' );
    }

    public function detalle()
    {
        return $this->hasOne( 'Dmkt\SolicitudDetalle' , 'id' , 'id_detalle' );
    }
    /* PROTECTED RELATIOSNSHIPS */

    protected function registerHist()
    {
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where('status_to' , REGISTRADO );
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
        return $this->hasMany( 'Dmkt\SolicitudClient' , 'id_solicitud' , 'id' );
    }

    public function createdBy()
    {
        return $this->belongsTo( 'User' , 'created_by' );
    }

    public function updatedBy()
    {
        return $this->belongsTo( 'User' , 'updated_by' );
    }

    public function gerente()
    {
        return $this->hasMany( 'Dmkt\SolicitudGer' , 'id_solicitud' , 'id' );
    }

    public function managerEdit( $userType )
    {
        return $this->hasMany( 'Dmkt\SolicitudGer' , 'id_solicitud'  , 'id' )->where( 'permiso' , 1 )->where( 'tipo_usuario' , $userType );
    }

    protected function assign()
    {
        return $this->belongsTo( 'User' , 'id_user_assign' );
    }

    protected function rm()
    {
        return $this->hasOne('Users\Rm','iduser','created_by')->select('nombres,apellidos,iduser');
    }

    protected function sup()
    {
        return $this->hasOne('Users\Sup','iduser','created_by')->select('nombres,apellidos,iduser,idsup');;
    }

    public function activity()
    {
        return $this->hasOne('Dmkt\Activity','id','id_actividad');
    }

    public function activityTrash()
    {
        return $this->hasOne( 'Dmkt\Activity' , 'id' , 'id_actividad' )->withTrashed();
    }

    protected function expenses()
    {
        return $this->hasMany( 'Expense\Expense' , 'id_solicitud' , 'id' )->orderBy( 'updated_at' , 'desc');
    }

    protected function lastExpense()
    {
        return $this->hasOne( 'Expense\Expense' , 'id_solicitud' , 'id' )->orderBy( 'updated_at' , 'desc' );
    }

    protected function baseEntry()
    {
        return $this->hasOne( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_BASE );
    }

    protected function depositEntrys()
    {
        return $this->hasMany( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_DEPOSITO );
    }

    public function policies()
    {
        return $this->hasMany( 'Policy\AprovalPolicy' , 'id_inversion' , 'id_inversion');
    }

    public function aprovalPolicy( $order )
    {
        return $this->hasMany( 'Policy\AprovalPolicy' , 'id_inversion' , 'id_inversion' )->where( 'orden' , $order )->first();            
    }

    public function userApprovalPolicy( $userType )
    {
        return $this->hasOne( 'Policy\AprovalPolicy' , 'id_inversion' , 'id_inversion' )->where( 'tipo_usuario' , $userType )->first();
    }

    protected function investment()
    {
        return $this->hasOne( 'Dmkt\InvestmentType' , 'id' , 'id_inversion' );
    }

    public function orderProducts()
    {
        return $this->hasMany( 'Dmkt\SolicitudProduct' , 'id_solicitud' )->orderBy( 'updated_at' , 'DESC' );
    }

    protected function getAllData()
    {
        return Solicitud::with( array( 'createdBy' => 'usuario' ) )->get();
    }
}