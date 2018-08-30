window.onload = function(){

    document.getElementById("is_borrowable").onclick = function(){
        var can_borrow = document.getElementsByClassName("can_borrow");
        if (this.checked) {
            Array.prototype.forEach.call(can_borrow, function(item){
                item.style.visibility = "visible";
            });
        }else{
            Array.prototype.forEach.call(can_borrow, function(item){
                item.style.visibility = "collapse";
            });
        }
    }

    document.getElementById("isnot_borrowable").onclick = function(){
        var can_borrow = document.getElementsByClassName("cannot_borrow");
        if (this.checked) {
            Array.prototype.forEach.call(can_borrow, function(item){
                item.style.visibility = "visible";
            });
        }else{
            Array.prototype.forEach.call(can_borrow, function(item){
                item.style.visibility = "collapse";
            });
        }
    }
}
