var todos = document.getElementById('todos');

if (todos) {
    todos.addEventListener('click', e =>{
        if (e.target.className === 'btn btn-danger borrar-todo') {
         if (confirm('Esta seguro ?')) {
             var id = e.target.getAttribute('data-id');
             
             fetch(` /todo/borrar/${id}`, {
                 method: 'BORRAR'
             }).then(res => window.location.reload());
         }
        }
    });
}
