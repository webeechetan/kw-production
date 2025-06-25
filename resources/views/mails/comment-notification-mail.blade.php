<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Invite | Kaykewalk</title>

    <style>
        .mxauto {
            margin: 0 auto;
        }
        .textCenter {
            text-align: center;
        }
        .spacing20 {
            padding: 20px;
        }
        img {
            max-width: 100%;
        }
        table {
            max-width: 100% !important;
        }
        body {
            background: #f7f7f7;
            font-family: trebuchet ms,sans-serif;
        }
        .emailer-wrap {
            margin: 0 auto;
            background: #fff;
        }
        .emailer-text-wrap {
            margin: 0 auto;
            padding: 50px 50px 70px;
        }
        .emailer-text-wrap h2 {
            font-size: 26px;
        }
        .emailer-text-wrap p {
            font-size: 18px;
            line-height: 26px;
        }
        .btn-primary {
            background: #F9398F;
            color: #fff;
            min-width: 200px;
            border-radius: 4px;
            padding: 15px 0;
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            text-align: center;
            text-transform: uppercase;
        }
        .list-unstyled{
            padding-left: 15px;
        }
        .list-unstyled li{
            margin-bottom: 10px
        }
    </style>
</head>
<body>
    <table class="main-emailer" width="100%" cellspacing="0" cellpadding="">
        <tbody>
            <tr>
                <td>
                    <table class="emailer-wrap" width="600" cellspacing="0" cellpadding="">
                        <tr>
                            <td>
                                <table width="100%" style="border-bottom: 1px solid #ddd;" cellspacing="0" cellpadding="">
                                    <tr>
                                        <td class="textCenter spacing20">
                                            <img src="https://kw.webeetest.tech/assets/images/logo.png" alt="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                       
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="">
                                    <tr>
                                        <td>
                                            <table class="emailer-text-wrap mxauto" width="100%" cellcellspacing="0" cellpadding="">
                                                <tr>
                                                    <td>
                                                        <h2 style="margin-top: 0; margin-bottom: 30px;">Hi there {{ $user->name }}!</h2>
                                                        @if($comment)
                                                            <p>{{ $mentioner->name }} has added a new comment in the task.</p>
                                                            <p>Task: {{ $task->name }}</p>
                                                            <p>Comment: {!! $comment->comment !!}</p>
                                                        @else
                                                            <p>{{ $task->assignedBy->name }} has mentioned you in a task.</p>
                                                            <p>Task: {{ $task->name }}</p>
                                                        @endif
                                                        <p>
                                                            Thanks,<br>
                                                            Kaykewalk Team
                                                        </p>
                                                         {{-- <p>A new internal comment has been added to <b>{{ $task->name }}</b></p>
                                                         
                                                        <a href="{{ $taskUrl }}"  class="btn-primary" style="margin-top: 30px;">View Task</a> --}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>