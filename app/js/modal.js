window.onload = () => {
  
    var book_registration_open = document.getElementById('book_registration_open');
    var user_registration_open = document.getElementById('user_registration_open');
    var close = document.getElementsByClassName('close');
    var book_registration_modal = document.getElementById('book_registration_modal');
    var user_registration_modal = document.getElementById('user_registration_modal');
    var mask = document.getElementById('mask');

    book_registration_open.addEventListener('click', () => {
        book_registration_modal.className = '';
        mask.className = '';
    });

    user_registration_open.addEventListener('click', () => {
        user_registration_modal.className = '';
        mask.className = '';
    });

    for(let i = 0; i < close.length; i++){
        close[i].addEventListener('click',hide);
    }

    mask.addEventListener('click', hide);
    
    function hide(){
        book_registration_modal.className = 'hidden';
        user_registration_modal.className = 'hidden';
        mask.className = 'hidden';  
    }
};
