<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use File;
use App\Models\Link;
use Illuminate\Support\Facades\Validator;
use App\Models\Activity;

class FileManager extends Component
{
    use WithFileUploads;

    public $client = null;
    public $project = null;
    public $files = [];
    public $directories = [];
    public $links = [];
    public $path = '';
    public $path_array = [];
    public $files_count = 0;
    public $links_count = 0;
    public $directories_count = 0;
    public $main_directory_files_count = 0;
    public $main_directory_links_count = 0;
    public $main_directory_directories_count = 0;
    public $main_directory_size = 0;

    // add new file
    public $new_file_name = '';
    public $new_file = null;

    // add new folder

    public $new_directory_name = '';

    // save link

    public $link_name = '';
    public $link_alias = '';

    // selected files
    public $selected_files = [];

    // selected directories
    public $selected_directories = [];

    // selected links

    public $selected_links = [];


    public $total_storage_size_in_mb = 100;
    public $used_storage_size_in_mb = 0;




    public function render()
    {
        return view('livewire.components.file-manager');
    }

    public function mount($client, $project = null)
    {
        $this->client = $client;
        if($project){
            $this->project = $project;
            $this->path = session('org_name').'/'.$this->client->name.'/'.$project->name;
        }else{
            $this->path = session('org_name').'/'.$this->client->name;
        }
        $this->getMainDirectoryMediaCount();
        $this->main_directory_size = $this->getDirectorySize($this->path);
        $this->path_array = explode('/', $this->path);
        $this->getMedia($this->path);
    }

    public function getMainDirectoryMediaCount(){
        $this->main_directory_files_count = count(Storage::files($this->path));
        $this->main_directory_links_count = Link::where('path', $this->path)->count();
        $this->main_directory_directories_count = count(Storage::directories($this->path));
    }

    public function getMedia($path){
        $this->files = [];
        $this->directories = [];

        $files = Storage::files($path);
        // get only file name and file size for each file

        foreach ($files as $file) {
            $file_array = explode('/', $file);
            $file_name = end($file_array);
            $this->files[$file_name] = [
                'size' => round(Storage::size($file)/1024,2),
                'file_path' => $file,
                'last_modified' => date('d M, Y h:i:s', Storage::lastModified($file)),
            ];
        }

        $directories = Storage::directories($path);
        // get only the last part of the path and get file and folder count for each directory
        foreach ($directories as $directory) {
            $directory_array = explode('/', $directory);
            $directory_name = end($directory_array);
            $this->directories[$directory_name] = [
                'files_count' => count(Storage::files($directory)),
                'links_count' => Link::where('path', $path)->count(),
                'directories_count' => count(Storage::directories($directory)),
                'directory_path' => $directory,
            ];
        }

        $this->files_count = count($this->files);
        $this->directories_count = count($this->directories);
        $this->used_storage_size_in_mb = $this->getDirectorySize($this->path);
        $this->links_count = Link::where('path', $path)->count();

        $this->links = $this->getLinks($this->path);
    }

    public function openFolder($path){
        $this->path = $path;
        $this->path_array = explode('/', $this->path);
        // remove last / from the path
        $this->path = rtrim($this->path, '/');
        $this->getMedia($this->path);
        $this->getLinks($path);
        $this->selected_files = [];
        $this->selected_directories = [];
        $this->selected_links = [];

    }

    public function goBack(){ 
        array_pop($this->path_array);
        $this->path = implode('/', $this->path_array);
        $this->getMedia($this->path);
    }

    public function addNewFile(){
        $rules = [
            'new_file' => 'required|max:51200',
        ];

        $messages = [
            'new_file.required' => 'Please select a file to upload',
            'new_file.max' => 'File size should not exceed 50MB',
        ];

        $res = Validator::make(['new_file' => $this->new_file], $rules, $messages);

        if($res->fails()){
            $this->dispatch('error', $res->errors()->first());
            return;
        }

        if($this->new_file_name == ''){
            $this->new_file_name = $this->new_file->getClientOriginalName();
        }else{
            $this->new_file_name = $this->new_file_name.'.'.$this->new_file->getClientOriginalExtension();
        }

        try{

            Storage::putFileAs($this->path, $this->new_file, $this->new_file_name);
            $this->new_file_name = '';
            $this->new_file = null;
            $this->getMedia($this->path);
            $this->dispatch('fileAdded');
            $this->dispatch('success', 'File added successfully');
            $this->getMainDirectoryMediaCount();
            $this->used_storage_size_in_mb = $this->getDirectorySize($this->path);
            $this->main_directory_size = $this->getDirectorySize($this->path);
            if($this->project){
                $activity = new Activity();
                $activity->org_id = session('org_id');
                $activity_text = 'Added a file '.$this->new_file_name.' to project <b>'.$this->project->name.'</b>';
                $activity->text = $activity_text;
                $activity->activityable_id = $this->project->id;
                $activity->activityable_type = 'App\Models\Project';
                $activity->created_by = auth()->guard(session('guard'))->user()->id;
                $activity->save();
            }
            
        }catch(\Exception $e){
            dd($e);
            session()->flash('error', 'File already exists');
        }

    }

    public function saveLink(){
        $this->validate([
            'link_name' => 'required'
        ]);

        $link = new Link();
        $link->org_id = session('org_id');
        $link->link = $this->link_name;

        $link->link_alias = $this->link_alias;
            
        $link->path = $this->path;
        $link->og_data = '';
        $link->save();
        $this->dispatch('linkAdded');
        $this->dispatch('success', 'Link added successfully');
        $this->getMedia($this->path);
        $this->getMainDirectoryMediaCount();
        $this->link_name = '';
        $this->link_alias = '';

    }

    public function selectFile($file_name, $selection_type = 'single'){
        if($selection_type == 'remove'){
            $this->selected_files = [];
            return;
        }

        if($selection_type == 'single'){
            $this->selected_files = [];
        }
        if(in_array($file_name, $this->selected_files)){
            $key = array_search($file_name, $this->selected_files);
            unset($this->selected_files[$key]);
        }else{
            $this->selected_files[] = $file_name;
        }
    }

    public function selectDirectory($directory_name, $selection_type = 'single'){
        if($selection_type == 'single'){
            $this->selected_directories = [];
        }
        if(in_array($directory_name, $this->selected_directories)){
            $key = array_search($directory_name, $this->selected_directories);
            unset($this->selected_directories[$key]);
        }else{
            $this->selected_directories[] = $directory_name;
        }
    } 

    public function selectLink($link_id, $selection_type = 'single'){
        if($selection_type == 'single'){
            $this->selected_links = [];
        }
        if(in_array($link_id, $this->selected_links)){
            $key = array_search($link_id, $this->selected_links);
            unset($this->selected_links[$key]);
        }else{
            $this->selected_links[] = $link_id;
        }
    }

    public function deleteSelected(){
        foreach ($this->selected_files as $file_name) {
            Storage::delete($file_name);
            if($this->project){
                $activity = new Activity();
                $activity->org_id = session('org_id');
                $activity_text = 'Deleted a file from project <b>'.$this->project->name.'</b>';
                $activity->text = $activity_text;
                $activity->activityable_id = $this->project->id;
                $activity->activityable_type = 'App\Models\Project';
                $activity->created_by = auth()->guard(session('guard'))->user()->id;
                $activity->save();
            }
        }
        foreach ($this->selected_directories as $directory_name) {
            Storage::deleteDirectory($directory_name);
        }
        foreach ($this->selected_links as $link_id) {
            Link::find($link_id)->delete();
        }

        $this->getMedia($this->path);
        $this->selected_files = [];
        $this->selected_directories = [];
        $this->dispatch('fileDeleted');
        $this->dispatch('success', 'Selected items deleted successfully');
    }
 
    public function addNewDirectory(){
        Storage::makeDirectory($this->path.'/'.$this->new_directory_name);
        $this->new_directory_name = '';
        $this->getMedia($this->path);
        $this->dispatch('directoryAdded');
        $this->dispatch('success', 'Directory added successfully');
        $this->getMainDirectoryMediaCount();
    }

    public function getDirectorySize($directory_path){
        $size = 0;
        $files = Storage::files($directory_path);
        foreach ($files as $file) {
            $size += Storage::size($file);
        }
        $directories = Storage::directories($directory_path);
        foreach ($directories as $directory) {
            $size += $this->getDirectorySize($directory);
        }

        return round($size/1024/1024, 2);
    }

    public function getLinks($path){
        return Link::where('path',$path)->get();
    }

    public function createThumbnailFromFileName($name){
        // check file name and create path according to file name
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
            return Storage::url($this->path.'/'.$name);
        } elseif ($extension == 'txt') {
            return asset('').'assets/images/icons/txt.svg';
        } elseif ($extension == 'pdf') {
            return asset('').'assets/images/icons/pdf.svg';
        } elseif ($extension == 'html'){
            return asset('').'assets/images/icons/html.svg';
        } elseif ($extension == 'psd'){
            return asset('').'assets/images/icons/psd.svg';
        } elseif ($extension == 'xlsx'){
            return asset('').'assets/images/icons/xlsx.svg';
        } elseif ($extension == 'sql'){
            return asset('').'assets/images/icons/sql.svg';
        } elseif ($extension == 'docx'){
            return asset('').'assets/images/icons/docx.svg';
        }else{
            return null;
        }

    }

    public function downloadSelected(){
        $files = $this->selected_files;
        // dd($files);
        $zip_file_name = 'download.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($files as $file) {
            $zip->addFile(Storage::path($file), $file);
        }
        $zip->close();
        return response()->download($zip_file_name)->deleteFileAfterSend(true);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <h1>Loading</h1>
        </div>
        HTML;
    }
}
