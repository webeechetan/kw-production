document.addEventListener('livewire:navigated', () => { 
    $('#project_id').select2();
}) 

document.addEventListener('task-form-toggled', function () {
    console.log('add task called')
    setTimeout(function(){
        initPlugins();
    }, 100);
});

document.addEventListener('edit-task-showed', function () {
    console.log('edit task called')
    setTimeout(function(){
        initPlugins();
    }, 100);
});

