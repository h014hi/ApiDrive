<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google\Service\Drive;
use Google\Service\Drive\Permission;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private function token(){
        $client_id=\Config('services.google.client_id');
        $client_secret=\Config('services.google.client_secret');
        $refresh_token=\Config('services.google.refresh_token');
        $response=Http::post('https://oauth2.googleapis.com/token',[
            'client_id'=>$client_id,
            'client_secret'=>$client_secret,
            'refresh_token'=>$refresh_token,
            'grant_type'=>'refresh_token',
        ]);

        $accessToken=json_decode((string)$response->getBody(),true)['access_token'];

        return $accessToken;
    }

    private function getDriveService(){
        $accessToken = $this->token();
        $client = new Google_Client();
        $client->setAccessToken($accessToken);
        $client->setScopes(['https://www.googleapis.com/auth/drive.file']);

        return [
            'client' => $client,
            'accessToken' => $accessToken,
        ];
    }

    public function crearCarpeta(Request $request, $id = null){
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            $nombreCarpeta = $request->input('folder_name');

            if($id!=null){
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nombreCarpeta,
                    'mimeType' => 'application/vnd.google-apps.folder',
                    'parents' => array($id),
                ]);
            }else{
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $nombreCarpeta,
                    'mimeType' => 'application/vnd.google-apps.folder',
                ]);
            }


            $folder = $service->files->create($fileMetadata, [
                'fields' => 'id',
            ]);

            return redirect()->back()->with('success', 'Se ha creado la carpeta');
        }catch (Exception $e) {
            echo "Error Message: ".$e;
        }
    }

    public function subirarchivos(Request $request, $id = null){
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            $name = Str::slug($request->file->getClientOriginalName());
            $file_path = $name;

            $file = new Google_Service_Drive_DriveFile();
            $file->setName($file_path);
            if($id!=null){
                $file->setParents(array($id));
            }

            $data = file_get_contents($request->file->path());

            $resultado = $service->files->create(
                $file,
                [
                    'data' => $data,
                    'mimeType' => $request->file->getClientMimeType(),
                    'uploadType' => 'media',
                ]
            );

            return redirect()->back()->with('success', 'Se ha subido correctamente su archivo');
        } catch (Google_Services_Exception $gs) {
            $mensaje = json_decode($gs->getMessage());
            return $mensaje->error->message;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function crearArchivo(Request $request, $id = null){
        try {
            $nombreArchivo = $request->input('nombre_archivo');
            $tipoArchivo = $request->input('tipo_archivo');

            $mimeType = '';

            switch ($tipoArchivo) {
                case 'xlsx':
                    $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    break;
                case 'docx':
                    $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                    break;
                case 'pptx':
                    $mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                    break;
            }

            /*if (!$mimeType) {
                return redirect()->back()->with('error', 'Error: Tipo de archivo no válido.');
            }*/

            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            $driveFile = new Google_Service_Drive_DriveFile();
            $driveFile->setName($nombreArchivo);
            $driveFile->setMimeType($mimeType);
            if($id!=null){
                $driveFile->setParents([$id]);
            }

            $createdFile = $service->files->create($driveFile);

            return redirect()->back()->with('success', 'Archivo creado exitosamente en Google Drive.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el archivo en Google Drive.');
        }
    }

    public function listarArchivos(Request $request){
        $driveServiceData = $this->getDriveService();
        $client = $driveServiceData['client'];
        $service = new Google_Service_Drive($client);

        // Define la consulta para obtener solo los archivos en la carpeta raíz y que no estén en la papelera
        $query = "'root' in parents and trashed = false";

        $resultado = $service->files->listFiles([
            'q' => $query,
        ]);

        $archivos = $resultado->getFiles();

        return view('welcome', ['archivos' => $archivos]);
    }

    public function visualizar(Request $request, $id){
        $driveServiceData = $this->getDriveService();
        $client = $driveServiceData['client'];
        $service = new Google_Service_Drive($client);

        $query = "'{$id}' in parents and trashed = false";
        $resultado = $service->files->listFiles([
            'q' => $query,
        ]);

        $archivos = $resultado->getFiles();

        return view('visualizar', ['archivos' => $archivos, 'id'=>$id]);
    }

    public function mostrar(Request $request, $id){
        $driveServiceData = $this->getDriveService();
        $client = $driveServiceData['client'];
        $service = new Google_Service_Drive($client);

        $resultado = $service->files->listFiles();

        $archivo = $resultado->getFiles();

        return view('editar', ['archivo' => $archivo, 'id'=>$id]);
    }

    public function editar(Request $request, $id) {
        $driveServiceData = $this->getDriveService();
        $client = $driveServiceData['client'];
        $service = new Google_Service_Drive($client);

        try {
            // Obtén la información actual del archivo
            $archivo = $service->files->get($id);

            // Crear un nuevo objeto Google_Service_Drive_DriveFile con el nuevo nombre
            $nuevoArchivo = new Google_Service_Drive_DriveFile();
            $nuevoArchivo->setName($request->input('nombre_edit'));

            // Realiza la actualización del archivo
            $resultado = $service->files->update($id, $nuevoArchivo);

            return redirect()->route('home')->with('success', 'Archivo actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error al actualizar el archivo: ' . $e->getMessage());
        }
    }

    public function eliminar($id){
        $driveServiceData = $this->getDriveService();
        $client = $driveServiceData['client'];
        $service = new Google_Service_Drive($client);

        try {
            $service->files->delete($id);
            return redirect()->route('home')->with('success', 'Carpeta eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error al eliminar la carpeta.');
        }
    }

    public function descargar($id) {
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            // Realiza la solicitud para obtener el archivo por ID
            $archivo = $service->files->get($id, array('alt' => 'media'));

            // Obtén el contenido del archivo BLOB
            $content = $archivo->getBody()->getContents();
            $contentType = $archivo->getHeader('Content-Type')[0];
            // Configura la respuesta para la descarga
            $response = new Response($content);
            $response->header('Content-Type', $contentType);
            $response->header('Content-Disposition', 'attachment; filename=Descarga'); // Puedes establecer un nombre de archivo aquí

            return $response;
        } catch (Exception $e) {
            echo 'Error al descargar el archivo: ' . $e->getMessage();
        }
    }

    public function shareMostrar(Request $request, $id) {
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            // Obtener la información de compartición del archivo
            $sharingInfo = $this->getSharingInfo($id);

            // Obtener la lista de archivos
            $resultado = $service->files->listFiles();
            $archivo = $resultado->getFiles();

            // Pasar la información de compartición a la vista
            return view('share', ['archivo' => $archivo, 'id' => $id, 'sharingInfo' => $sharingInfo]);
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            return redirect()->back()->with('error', 'Error al obtener la información de compartición.');
        }
    }

    public function shareFiles(Request $request, $id){
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            // Obtener el correo al que deseas compartir el archivo
            $correoDestino = $request->input('email');
            $rolArchivo = $request->input('rolArchivo');

            // Crear un objeto de permisos para compartir el archivo
            $permission = new Permission([
                'type' => 'user',
                'role' => $rolArchivo, // Puedes cambiar 'reader' por 'writer' si quieres dar permisos de escritura
                'emailAddress' => $correoDestino,
            ]);

            // Enviar solicitud para agregar el permiso al archivo
            $service->permissions->create($id, $permission);
            return redirect()->route('share')->with('success', 'Carpeta eliminada correctamente.');
        } catch (\Exception $e) {
            echo '';
        }
    }

    public function getSharingInfo($id){
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Google_Service_Drive($client);

            // Obtén la lista de permisos del archivo
            $permissions = $service->permissions->listPermissions($id);

            // Itera sobre la lista de permisos
            foreach ($permissions as $permission) {
                // Puedes acceder a la información sobre el permiso
                $role = $permission->getRole();
                $type = $permission->getType();
                $emailAddress = $permission->getEmailAddress();
                $domain = $permission->getDomain();
            }
            // Devuelve la información recopilada
            return ($permissions);
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante la obtención de la información
            return null;
        }
    }

    public function eliminarAcceso($fileId, $permissionId){
        try {
            $driveServiceData = $this->getDriveService();
            $client = $driveServiceData['client'];
            $service = new Drive($client);

            // Eliminar el permiso específico
            $service->permissions->delete($fileId, $permissionId);

            return redirect()->back()->with('success', 'Acceso eliminado correctamente.');
        } catch (Google_Service_Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el acceso.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

}
