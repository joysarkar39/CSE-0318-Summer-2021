r(function(){
    var snackbarContainer = document.querySelector('#demo-toast-example');
    var data = { message: 'Example Message.'};
    snackbarContainer.MaterialSnackbar.showSnackbar(data);
});
function r(f){ /in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}