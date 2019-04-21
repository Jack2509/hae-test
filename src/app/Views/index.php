<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guest book</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom styles for this template -->
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>

<div class="row no-gutters">
    <div class="col-sm-12 col-md-12 col-lg-3 border-bottom pb-5">
        <div class="row align-content-center mr-auto">
            <img src="assets/images/logo-hae-group.png" class="img-responsive" alt="Cinque Terre">
        </div>
        <div class="horizontal-bar"></div>
        <div class="row">
            <div class="title">GuestBook</div>
        </div>
        <div class="row">
            <div class="description pl-5 pr-5">
                Feel free to leave us a short message
                to tell us what you think to our services
            </div>
        </div>
        <div class="row mt-5">
            <button type="button" class="mg-auto btn btn-danger btn-danger-custom" data-toggle="modal" data-target="#create-message-modal">
                Post a Message
            </button>
        </div>
        <div class="row mt-5">
            <?php if(isset($_SESSION['login_flag']) && $_SESSION['login_flag']) { ?>
            <button type="button" class="mg-auto btn btn-light" data-toggle="modal" data-target="#logout-modal">
                Admin Log out
            </button>
            <?php } else { ?>
            <button type="button" class="mg-auto btn btn-light" data-toggle="modal" data-target="#login-modal">
                Admin log in
            </button>
            <?php } ?>
        </div>
        <br>
        <br>
        <?php if (isset($_SESSION['login_error_message']) && $_SESSION['login_error_message']) { ?>
        <div class="alert alert-warning">
            <strong>Warning!</strong> <?php echo $_SESSION['login_error_message']; ?>
        </div>
        <?php $_SESSION['login_error_message'] = false;} ?>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-9 main-bg-color color-white pl-5 pr-5">
        <?php
        $countComments = count($commentsArr);
        if ($countComments) {
        for ($i = 0; $i < $countComments || $i == $countComments - 1; $i+=2) {
            if ($i == $countComments - 1) {
        ?>
        <div class="row">
            <div class="col-md-6 pb-5 pt-5">
                <div class="comment"><?php echo $commentsArr[$i]['content']; ?></div>
                <div class="row info-container pt-3 pb-3">
                    <div class="col-sm-8 col-7">
                        <div class="row name"><?php echo $commentsArr[$i]['name']; ?></div>
                        <div class="row"><?php echo date('dS M, Y', strtotime($commentsArr[$i]['comment_updated_at'])) . ' at ' . date('h:ia', strtotime($commentsArr[$i]['comment_updated_at'])); ?></div>
                    </div>
                    <?php if(isset($_SESSION['login_flag']) && $_SESSION['login_flag']) { ?>
                    <div class="cta-wrapper col-sm-4 col-5">
                        <div class="cta">
                            <button
                                    type="button"
                                    id="btn-update-message-<?php echo $commentsArr[$i]['id']?>"
                                    class="btn btn-default btn-circle bg-color-circle-btn"
                                    data-toggle="modal" data-target="#update-message-modal">
                                <i class="fa fa-pencil color-white" aria-hidden="true"></i>
                            </button>
                            <button
                                    type="button"
                                    id="btn-delete-message-<?php echo $commentsArr[$i]['id']?>"
                                    class="btn btn-default btn-circle bg-color-circle-btn">
                                <i class="fa fa-trash color-white" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } else {?>
        <div class="row">
            <?php for ($j = $i; $j <= $i + 1; $j++) { ?>
            <div class="col-md-6 pb-5 pt-5">
                <div class="comment"><?php echo $commentsArr[$j]['content']; ?></div>
                <div class="row info-container pt-3 pb-3">
                    <div class="col-sm-8 col-7">
                        <div class="row name"><?php echo $commentsArr[$j]['name']; ?></div>
                        <div class="row"><?php echo date('dS M, Y', strtotime($commentsArr[$j]['comment_updated_at'])) . ' at ' . date('h:ia', strtotime($commentsArr[$j]['comment_updated_at'])); ?></div>
                    </div>
                    <?php if(isset($_SESSION['login_flag']) && $_SESSION['login_flag']) { ?>
                    <div class="cta-wrapper col-sm-4 col-5">
                        <div class="cta">
                            <button
                                    type="button"
                                    id="btn-update-message-<?php echo $commentsArr[$j]['id']?>"
                                    class="btn btn-default btn-circle bg-color-circle-btn"
                                    data-toggle="modal" data-target="#update-message-modal">
                                <i class="fa fa-pencil color-white" aria-hidden="true"></i>
                            </button>
                            <button
                                    type="button"
                                    id="btn-delete-message-<?php echo $commentsArr[$j]['id']?>"
                                    class="btn btn-default btn-circle bg-color-circle-btn">
                                <i class="fa fa-trash color-white" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php }?>
        <?php }?>
        <ul class="pagination">
        <?php
            // Establish the $pagination variable
            $pagination = '';
            if ($pagesTotal != 1) {

                /**
                 * First we check if we are on page one. If we are then we don't need a link to
                 * the previous page or the first page so we do nothing. If we aren't then we
                 * generate links to the first page, and to the previous page.
                 **/
                if ($currentPage > 1) {
                    $previous = $currentPage - 1;
                    $pagination .= '<li class="page-item"><a class="" href="?page=' . $previous . '"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>';
                    // Render clickable number links that should appear on the left of the target page number
                    $numberOfPagesLeft = $currentPage == $pagesTotal ? $currentPage - 2 : $currentPage - 1;
                    for ($i = $numberOfPagesLeft; $i < $currentPage; $i++) {
                        if ($i > 0) {
                            $pagination .= '<li class="page-item"><a class="page-link btn-circle" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                    }
                }

                // Render the current page number
                $pagination .= '<li class="page-item"><a class="page-link btn-circle bg-color-circle-btn">' . $currentPage . '</a></li>';

                // Render clickable number links that should appear on the right of the target page number
                for($i = $currentPage + 1; $i <= $pagesTotal; $i++){
                    $pagination .= '<li class="page-item"><a class="page-link btn-circle" href="?page=' . $i . '">' . $i . '</a></li>';
                    $numberOfPagesRight = $currentPage == 1 ? $currentPage  + 2 : $currentPage  + 1;
                    if($i >= $numberOfPagesRight) {
                        break;
                    }
                }

                // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                if ($currentPage != $pagesTotal) {
                    $next = $currentPage + 1;
                    $pagination .= '<li class="page-item"><a class="" href="?page=' . $next . '"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>';
                }
            }
            echo $pagination;
        ?>
        </ul>
        <?php }?>
    </div>
</div>

<!-- Post message form -->
<div class="modal" id="create-message-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="create-message-form" action="/api/comments/create" method="post">
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Post new message</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <small id="create-error-message" class="text-danger"></small>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-danger">Post message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit message form -->
<div class="modal" id="update-message-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update-message-form" action="/api/comments/update" method="post">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Update message</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="update-message" name="update_message" rows="3"></textarea>
                        <input type="hidden" id="update-message-id" name="message_id">
                    </div>
                </div>
                <div class="col-12">
                    <small id="update-error-message" class="text-danger"></small>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-danger">Update message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Login form -->
<div class="modal" id="login-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="login-form" action="/admin/login" method="post">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Login form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Email address</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="col-12">
                    <small id="login-error-message" class="text-danger"></small>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-danger">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Logout form -->
<div class="modal" id="logout-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="logout-form" action="/admin/logout" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Are you sure to log out?
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" name="submit" value="yes" class="btn btn-danger">Yes</button>
                    <button class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- loading -->
<div class="loading hidden" id="loading">Loading&#8230;</div>

<!-- Bootstrap core JavaScript -->
<script src="/assets/js/jquery.js"></script>
<script src="/assets/js/bootstrap.bundle.js"></script>

<script>
$(document).ready(function() {
    $('#create-message-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            type: 'post',
            url: $form.attr('action'),
            data: $form.serialize(),
            beforeSend: function(){
                $("#loading").toggleClass('hidden');
            },
            complete: function(){
                $("#loading").toggleClass('hidden');
            },
            success: function (response) {
                response.message ? window.location.href = '/' : alert('Can not create new comment. Please try again !');
            },
            error: function(xhr, status, error) {
                var message = $.parseJSON(xhr.responseText);
                $('#login-error-message').html(message.message);
            }
        });

    });

    $('#update-message-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            type: 'post',
            url: $form.attr('action'),
            data: $form.serialize(),
            beforeSend: function(){
                $("#loading").toggleClass('hidden');
            },
            complete: function(){
                $("#loading").toggleClass('hidden');
            },
            success: function (response) {
                response.message ? window.location.href = '/' : alert('Can not update comment. Please try again !');
            },
            error: function(xhr, status, error) {
                var message = $.parseJSON(xhr.responseText);
                $('#create-error-message').html(message.message);
            }
        });

    });


    $('button[id^="btn-update-message-"]').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this),
            idMessage = $btn.attr('id').split('-')[3];

        $.ajax({
            type: 'get',
            url: '/api/comments/read',
            data: {id:idMessage},
            beforeSend: function(){
                $("#loading").toggleClass('hidden');
            },
            complete: function(){
                $("#loading").toggleClass('hidden');
            },
            success: function (response) {
                var messageObj = response.records[0];
                $('textarea#update-message').html(messageObj.content);
                $('#update-message-id').val(messageObj.id);
            },
            error: function(xhr, status, error) {
                var message = $.parseJSON(xhr.responseText);
                $('#update-error-message').html(message.message);
            }
        });

    });

    $('button[id^="btn-delete-message-"]').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this),
            idMessage = $btn.attr('id').split('-')[3];
        console.log(idMessage);
        if (confirm('Are you sure to delete this message ?')) {
            //Delete message
            $.ajax({
                type: 'post',
                url: '/api/comments/delete',
                data: {id:idMessage},
                beforeSend: function(){
                    $("#loading").toggleClass('hidden');
                },
                complete: function(){
                    $("#loading").toggleClass('hidden');
                },
                success: function (response) {
                    var searchParams = new URLSearchParams(window.location.search),
                        currentPage = searchParams.has('page') ? searchParams.get('page') : '';
                    response.message ? window.location.href = '/?page=' + currentPage : alert('Can not delete this comment. Please try again !');
                },
                error: function(xhr, status, error) {
                    var message = $.parseJSON(xhr.responseText);
                    alert(message.message);
                }
            });
        } else {
            //Do nothing
        }
    });

});
</script>
</body>
</html>