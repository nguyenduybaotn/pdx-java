<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NBC e-Office</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
        
        #img-upload {
            width: 100%;
        }
    </style>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function() {
                readURL(this);
            });
        });
    </script>
</head>

<body>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <div class="container" style="padding-top:50px;">
        <div class='row'>
            <h4> Danh sách các hình của văn bản <?php echo $idvanban;?></h4>

            <?php 
			$url = 'http://localhost:8080/api/vanban/getImage/'.$idvanban;

			$code = $_SESSION['code'];
			$curl = curl_init();

			// Definition of request's headers
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
				"code: $code",
				"cache-control: no-cache",
				"content-type: application/json"
			  ),
			));

			// Send request and show response
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
				$images = json_decode($response);
				foreach($images as $img){
					//echo $img->path;
					
					echo "<div class='row' style='margin-top:15px;'><div class='col-lg-12 col-md-12 col-xs-12 col-sm-12'>
					<img style='width:100px;margin-right:15px;' src='data:image/jpg;base64,".$img->path_encode."' />
					<button type='button' image_path='".$img->path."' id='xoaimage' class='btn btn-sm btn-warning xoaimage' >Xóa</button></div></div>"; 
					
				}
			}
			?>
                <div class='row'>
                    <div class='col-lg-12 col-md-12 col-xs-12 col-sm-12' style="padding-top:10px;">
                        <button type='button' class="btn btn-sm btn-success" id="xacnhan">Xác nhận hình ký duyệt</button>
                    </div>
                </div>
        </div>
        <div class='row' style="padding-top:50px;">
            <div class="col-md-12">
                <form action="" method="POST" role="form" enctype="multipart/form-data">
                    <legend>Tải hình văn bản đã được ký sống</legend>
                    <input type='hidden' name='description' id='description' />
                    <input type='hidden' name='idvanban' id='idvanban' value="<?php echo $idvanban; ?>" />
                    <div class="form-group">
                        <label for="file">Chọn file</label>
                        <input id="fileDatas" type="file" name="fileDatas" required="" />
                    </div>
                    <div class="form-group">
                        <button id="upload" type="button" class="btn btn-success">Tải lên</button>
                        <a id="" type="button" href="<?php echo base_url('welcome')?>" class="btn btn-primary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript">
    $(function() {
        $('#upload').click(function(e) {
            e.preventDefault();
            //Disable submit button
            $(this).prop('disabled', true);

            var form = document.forms[0];
            var formData = new FormData(form);
            // Ajax call for file uploaling
            var ajaxReq = $.ajax({
                url: 'http://192.168.14.139:8080/api/uploadOneFile',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                xhr: function() {
                    //Get XmlHttpRequest object
                    var xhr = $.ajaxSettings.xhr();
                    //Set onprogress event handler 
                    xhr.upload.onprogress = function(event) {
                        alert("Upload thành công");
                        location.reload();
                    };
					alert(xhr.readyState);
                    return xhr;
                }
            });

            // Called on success of file upload
            ajaxReq.done(function(msg) {
                alert("Called on success of file upload");
            });

            // Called on failure of file upload
            ajaxReq.fail(function(jqXHR) {
                alert("Called on failure of file upload");
            });
        });
        
    });
</script>
<script type="text/javascript">
	$('.xoaimage').click(function() {
                var path = $(this).attr('image_path');
				console.log(path);
				
                //sử dụng ajax post
                $.ajax({
                        url: 'http://192.168.14.139:8080/api/vanban/delImage/'+path,
                        data: {
                            code:'<?php echo $code;?>'
                            
                        },
						traditional: true,
                        type: 'DELETE',
                        success: function(res) {
                            console.log(res);
							if(res){
								// xóa thành công
								alert('Đã xóa');
							}else{
								// xóa bị lỗi
								alert('Không xóa được');
							}
							location.reload();
                        }
                });
        });
</script>
<script type="text/javascript">
	$('#xacnhan').click(function() {
                var idvanban = '<?php echo $idvanban;?>';
				console.log(idvanban);
				
                //sử dụng ajax post
                $.ajax({
                        url: 'http://192.168.14.139:8080/api/vanban/'+idvanban,
						contentType: 'application/json',
                        data: JSON.stringify({
                            'code':'<?php echo $code;?>',
                            'tinhtrang':'2'
                            
                        }),
						traditional: true,
                        type: 'PUT',
                        success: function(res) {
                            console.log(res);
							if(res){
								// xóa thành công
								alert('Xác nhận thành công');
							}else{
								// xóa bị lỗi
								alert('Không xác nhận được');
							}
							location.reload();
                        }
                });
        });
</script>

</html>