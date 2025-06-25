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
            background: #fffdf2;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .emailer-text-wrap {
            margin: 0 auto;
            padding: 0 40px 40px;
        }
        .emailer-text-wrap h2 {
            font-size: 26px;
        }
        .emailer-text-wrap p {
            font-size: 18px;
            line-height: 26px;
        }
        .btn-primary {
            background: #F9398F !important;
            color: #fff !important;
            border-radius: 4px;
            padding: 10px 30px;
            display: block;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
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
                                                        @if($newStatus=='completed')
                                                            <p>{{ $changedBy->name }} marked <strong>{{ Str::limit($task->name, 15, '...') }}</strong> as Complete.</p>
                                                        @else
                                                            <p>{{ $changedBy->name }} Changed the status of <strong>{{ Str::limit($task->name, 15, '...') }}</strong> from <strong>{{ $oldStatus}}</strong> to <strong>{{ $newStatus }}</strong></p>
                                                        @endif
                                                        {{-- <p>  assigned you a task {{ Str::limit($task->name, 15, '...') }} in </p> --}}
                                                        <a href="{{ route('task.view',$task->id) }}"  class="btn-primary" style="margin-top: 30px;">View Task</a>
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