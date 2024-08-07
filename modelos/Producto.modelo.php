<?php

require_once "Conexion.php";

class ModeloProducto{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProducto($tablaC, $tablaP, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * from $tablaC as c inner join $tablaP as p on c.id_categoria = p.id_categoria WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * from $tablaC as c inner join $tablaP as p on c.id_categoria = p.id_categoria ORDER BY p.id_producto DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		


		$stmt = null;

	}

	/*=============================================
	MOSTRAR PRODUCTOS NUEVOS
	=============================================*/

	static public function mdlMostrarProductoNuevos($tablaC, $tablaP, $item, $valor) {

		if ($item != null) {
	
			$stmt = Conexion::conectar()->prepare("SELECT * from $tablaC as c inner join $tablaP as p on c.id_categoria = p.id_categoria WHERE $item = :$item");
	
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
	
			$stmt -> execute();
	
			return $stmt -> fetch();
	
		} else {
	
			// Ajuste de la consulta para obtener los 5 productos más recientes
			$stmt = Conexion::conectar()->prepare("SELECT * from $tablaC as c inner join $tablaP as p on c.id_categoria = p.id_categoria ORDER BY p.fecha_producto DESC LIMIT 5");
	
			$stmt -> execute();
	
			return $stmt -> fetchAll();
	
		}
	
		$stmt = null;
	
	}
	

	/*=============================================
	MOSTRAR PRODUCTOS STOCK
	=============================================*/

	static public function mdlMostrarProductoStock($tablaC, $tablaP, $item, $valor)
	{

		$stmt = Conexion::conectar()->prepare("SELECT * from $tablaC as c inner join $tablaP as p on c.id_categoria = p.id_categoria WHERE p.stock_producto <= $valor ORDER BY p.id_producto DESC");

		$stmt->execute();

		return $stmt->fetchAll();

	}


	/*=============================================
	MOSTRAR PRODUCTOS FECHA DE VENCIMIENTO
	=============================================*/

	static public function mdlMostrarProductoFechaVencimiento($tablaC, $tablaP, $item, $valor)
	{

		if($item == null && $valor == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaC INNER JOIN  $tablaP ON $tablaC.id_categoria=$tablaP.id_categoria WHERE $tablaP.fecha_vencimiento BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 10 DAY)");

			$stmt->execute();
	
			return $stmt->fetchAll();
		}



	}


	/*=============================================
	REGISTRO DE PRODUCTOS
	=============================================*/

	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(
                                                                id_categoria,
                                                                codigo_producto, 
                                                                nombre_producto, 
                                                                stock_producto, 
                                                                fecha_vencimiento, 
                                                                descripcion_producto, 
                                                                imagen_producto) 
                                                                VALUES (
                                                                :id_categoria, 
                                                                :codigo_producto, 
                                                                :nombre_producto, 
                                                                :stock_producto, 
                                                                :fecha_vencimiento, 
                                                                :descripcion_producto, 
                                                                :imagen_producto)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo_producto", $datos["codigo_producto"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre_producto", $datos["nombre_producto"], PDO::PARAM_STR);
		$stmt->bindParam(":stock_producto", $datos["stock_producto"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_producto", $datos["descripcion_producto"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen_producto", $datos["imagen_producto"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";	

		}else{

			return "error";
		
		}

		
		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTOS
	=============================================*/

	static public function mdlEditarProducto($tabla, $datos){
	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
																id_categoria = :id_categoria, 
																codigo_producto = :codigo_producto, 
																nombre_producto = :nombre_producto, 
																stock_producto = :stock_producto, 
																fecha_vencimiento = :fecha_vencimiento, 
																descripcion_producto = :descripcion_producto, 
																imagen_producto = :imagen_producto
																WHERE id_producto = :id_producto");

		$stmt -> bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt -> bindParam(":codigo_producto", $datos["codigo_producto"], PDO::PARAM_STR);
		$stmt -> bindParam(":nombre_producto", $datos["nombre_producto"], PDO::PARAM_STR);
		$stmt -> bindParam(":stock_producto", $datos["stock_producto"], PDO::PARAM_INT);
		$stmt -> bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
		$stmt -> bindParam(":descripcion_producto", $datos["descripcion_producto"], PDO::PARAM_STR);
		$stmt -> bindParam(":imagen_producto", $datos["imagen_producto"], PDO::PARAM_STR);
		$stmt -> bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTOS
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $item2, $valor2){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;

	}

	/*=============================================
	BORRAR PRODUCTOS
	=============================================*/

	static public function mdlBorrarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_producto = :id_producto");

		$stmt -> bindParam(":id_producto", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;


	}

}
