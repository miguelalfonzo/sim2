<?php

namespace Dmkt;

use \BaseController;
use \Input;
use \Exception;

class ProductController extends BaseController
{

    public function deleteSolicitudProduct()
    {
    	$inputs = Input::all();
    	$this->removeSolicitudProduct( $inputs[ 'id_solicitud_producto' ] );
    }

    public function removeSolicitudProduct( $idSolicitudProducto )
    {
		SolicitudProduct::find( $idSolicitudProducto )->delete();    	
    }

    public function addSolicitudProduct()
    {
    	$inpurs = Input::all();
    	$this->newSolicitudProduct( $inputs );
    }

    public function newSolicitudProduct( $inputs )
    {
		$solProduct               = new SolicitudProduct;
		$solProduct->id           = $solProduct->lastId() + 1;
		$solProduct->id_solicitud = $inputs[ 'id_solicitud' ];
		$solProduct->id_producto  = $inputs[ 'id_producto' ];
		$solProduct->save();
    }

    public function unsetSolicitudProducts( $solicitudId , $productId )
    {
        $solicitud = Solicitud::find( $solicitudId );
        $solicitudProducts = $solicitud->products;
        $aux = 0;

        foreach ( $solicitudProducts as $solicitudProduct )
        {
            if ( ! is_null( $solicitudProduct->id_fondo_marketing ) && ! is_null( $solicitudProduct->id_tipo_fondo_marketing ) )
            {
                $aux = 1;
                break;
            }
        }

        if ( $aux === 1 )
        {
            $solicitudController = new solicitudController;
            $solicitudController->renovateBalance( $solicitudId )
        }

        $solicitudProducts->delete();

        $solicitudProductsId = array();
        foreach( $productsId as $productId )
        {
            $data = array(
                'id_solicitud' => $solicitudId ,
                'id_producto'  => $productId );
            $this->newSolicitudProduct( $data );
            $solicitudProductsId[] = SolicitudProduct::where( 'id_solicitud' , $solicitudId )->where( 'id_producto' , $productId )->first()->id;
        }
        return $this->setRpta( $solicitudProductsId );
    } 

}