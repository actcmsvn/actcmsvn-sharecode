<html>
<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//sharecode.actcms.work/plugins/actcmsvn-helper/helper.css">
    <style>
        .invalid-feedback {
            color: darkred;
        }
    </style>
</head>
<body>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Xác minh giao dịch mua của bạn</h1>
            <p style="padding: 5px" class="bg-primary">Đối với tên miền:- {{ \request()->getHost() }}</p>

            <p style="margin: 20px 0">
                <span class="label label-warning">CẢNH BÁO</span>
                Liên hệ với quản trị viên của bạn nếu bạn không phải là quản trị viên để xác minh giao dịch mua.

            </p>

            <p style="margin: 20px 0">
                <span class="label label-danger">GHI CHÚ</span>
                Nhấn vào <a href="https://sharecode.vn"
                         target="_blank">liên kết này</a> để tìm mã mua hàng của bạn

            </p>

            <div id="response-message"></div>

            <form action="" id="verify-form">
                <div class="form-body">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nhập mã mua mã nguồn trên Sharecode của bạn</label>
                                <input type="text" id="purchase_code" name="purchase_code" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <button class="btn btn-success" type="button" id="verify-purchase"
                                        onclick="validateCode();return false;">Xác minh
                                </button>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//sharecode.actcms.work/plugins/actcmsvn-helper/helper.js"></script>

<script>

    function validateCode() {
        $.easyAjax({
            type: 'POST',
            url: "{{ route('purchase-verified') }}",
            data: $("#verify-form").serialize(),
            container: "#verify-form",
            messagePosition: 'inline'
        });
        return false;
    }


</script>

</body>
</html>
