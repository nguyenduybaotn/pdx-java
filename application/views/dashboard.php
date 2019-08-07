<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NBC e-Office</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
			font-size:12px;
        }
        a.btn {font-size:12px;}
        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</head>

<body>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
	<div class="container pt-5">
	<p><a href='<?php echo base_url('welcome/logout')?>'>Logout</a></p>
    <h2>Danh sách phiếu đề xuất của <?php echo $this->tool_model->getNhanVien($_SESSION['idnhanvien'])->hoTen;?></h2>
    <p>Chỉ dành cho pdx cần ký offline: bao, chỉ , thùng...</p>
    <input id="myInput" type="text" placeholder="Search..">
    <br>
    <br>
	<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Mã văn bản</th>
                <th scope="col">Tiêu đề</th>
                <th scope="col">Ngày tạo</th>
                <th scope="col">Mô tả</th>
                <th scope="col"></th>
            </tr>
        </thead>
		<?php 
		/*
		// get all van ban
		// API URL
		$url = 'http://localhost:8080/api/vanban';
		//code
		$code = $_SESSION['code'];
		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json","code:$code"));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		$result = curl_exec($ch);
		$json_result = json_decode($result);
		echo print_r($result);
		// Close cURL resource
		curl_close($ch);	
		*/
		$url = 'http://localhost:8080/api/vanban';
		
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
			//echo $response;
			$vanbans = json_decode($response);
			
			$i=1;
			if(count($vanbans)){
			foreach($vanbans as $vb){
				//if($i=1) print_r($vb);
				// get ten nhan vien
				if($vb->tinhTrangXetDuyet==1){
			
		?>
        <tbody id="myTable">
            <tr>
                <th scope="row"><?php echo $i;?></td>
                <td><?php echo $vb->idVanBan;?></td>
                <td><?php echo $vb->tieuDe;?></td>
                <td><?php echo $vb->ngayTao;?></td>
                <td><?php echo $vb->mota;?></td>
                <td>
					<a class="btn btn-sm btn-danger" href='<?php echo base_url('welcome/uphinh/'.$vb->idVanBan)?>' >Upload</a>
				</td>
            </tr>
        </tbody>
				<?php $i++;} }
		}} ?>
    </table>
	</div>
    <p>Note that we start the search in tbody, to prevent filtering the table headers.</p>
	</div>
</body>

</html>