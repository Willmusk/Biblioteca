<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Libro;

class Libros extends Controller{
 /*
 * --------------------------------------------------------------------
 * Funcion
 * --------------------------------------------------------------------
 */
public function index (){

        $libro=new Libro();
        $datos['libros']=$libro->orderby('id','ASC')->findAll();
        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/piedepagina');
    
        return view ('libros/listar',$datos);
    }
 /*
 * --------------------------------------------------------------------
 * Funcion Crear
 * --------------------------------------------------------------------
 */
public function crear (){

        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/piedepagina');

         return view ('libros/crear', $datos);
    }
 /*
 * --------------------------------------------------------------------
 * Funcion guardar
 * --------------------------------------------------------------------
 */
public function guardar (){

        $libro= new Libro();
        if($imagen=$this->request->getFile('imagen')){
        $nuevoNombre= $imagen->getRandomName();
        $imagen->move('../public/uploads/',$nuevoNombre);

                $datos=[
                    'nombre'=>$this->request->getVar('nombre'),
                    'imagen'=>$nuevoNombre
                ];
                $libro->insert($datos);

           }

           return $this->response->redirect(site_url('/listar'));
        }
 /*
 * --------------------------------------------------------------------
 * Funcion borrar
 * --------------------------------------------------------------------
 */
        public function borrar($id=null){
            $libro= new Libro();
            $datosLibro=$libro->where('id',$id)->first();

            $ruta=('../public/uploads/'.$datosLibro['imagen']);
            unlink($ruta);
            
            $libro->where('id',$id)->delete($id);

            return $this->response->redirect(site_url('/listar'));
        }
 /*
 * --------------------------------------------------------------------
 * Funcion editar
 * --------------------------------------------------------------------
 */
        public function editar($id=null){

            print_r($id);
            $libro= new Libro();

            $datos['libro']=$libro->where('id',$id)->first();

            $datos['cabecera']=view('template/cabecera');
            $datos['pie']=view('template/piedepagina');

            return view('libros/editar',$datos);
        }
 /*
 * --------------------------------------------------------------------
 * Funcion actualizar
 * --------------------------------------------------------------------
 */
        public function actualizar (){

            $libro= new Libro();
            $datos=[
            'nombre'=>$this->request->getVar('nombre')
            ];

            $id= $this->request->getVar('id');

            $libro->update($id,$datos);

/*
 * --------------------------------------------------------------------
 * Validacion 
 * --------------------------------------------------------------------
 */            

            $validacion = $this->validate([
                'imagen' => [
                        'uploaded[imagen]',
                        'mime_in[imagen,image/jpg,image/jpeg,image/png]',
                        'max_size[imagen,1024]',

                   ]

            ]);

            if($validacion){

                if($imagen=$this->request->getFile('imagen')){

                    $datosLibro=$libro->where('id',$id)->first();

                    $ruta=('../public/uploads/'.$datosLibro['imagen']);
                    unlink($ruta);

                    $nuevoNombre= $imagen->getRandomName();
                    $imagen->move('../public/uploads/',$nuevoNombre);
    
                    $datos=['imagen'=>$nuevoNombre];
                    $libro->update($id,$datos);
    
               }
                



            }

        }

}
