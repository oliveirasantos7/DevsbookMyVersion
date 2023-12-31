
<?php
$firstName = current(explode(' ', $userInfo->name));
?>

<div class="box feed-new">
                        <div class="box-body">
                            <div class="feed-new-editor m-10 row">
                                <div class="feed-new-avatar">
                                    <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" />
                                </div>
                                <div class="feed-new-input-placeholder">O que você está pensando, <?=$firstName;?>?</div>


                    
                                <div class="feed-new-input" contenteditable="true"></div>
                                <div class="feed-new-send">
                                    <img src="<?=$base;?>/assets/images/send.png" />
                                </div>

                                <form class="feed-new-form" action="<?=$base;?>/feedEditorAction.php" method="post">

                                <input type="hidden" name="body">
                            </form>

                            </div>
                        </div>
                    </div>

 <script>


    let feedInput = document.querySelector('.feed-new-input');
let feedSubmit = document.querySelector('.feed-new-send');
let feedForm = document.querySelector('.feed-new-form');

feedSubmit.addEventListener('click', function(){
    let value = feedInput.innerText.trim();
    feedForm.querySelector('input[name=body]').value = value;
    feedForm.submit();
});





    document.querySelector('.feed-new-input-placeholder').addEventListener('click', function(obj){
    obj.target.style.display = 'none';
    document.querySelector('.feed-new-input').style.display = 'block';
    document.querySelector('.feed-new-input').focus();
    document.querySelector('.feed-new-input').innerText = '';
});

document.querySelector('.feed-new-input').addEventListener('blur', function(obj) {
    let value = obj.target.innerText.trim();
    if(value == '') {
        obj.target.style.display = 'none';
        document.querySelector('.feed-new-input-placeholder').style.display = 'block';
    }
});

 </script>

 