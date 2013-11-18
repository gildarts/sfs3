<?php
/*
	
*/


	class HTTP {
	
		public function PostXml($url, $req)
		{
			$ch = curl_init($url);
		
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //skip ssl verify
			
			$response = (string)curl_exec($ch);
			curl_close($ch);
			
			return $response;
		}
		
		public function Post($url, $req) {
			//echo $url . "?" . $req;	
			//die("before send request");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	//quick fix for SSL
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //skip ssl verify
                                                            
			$response = curl_exec($ch);
			curl_close ($ch);
			
			//echo "response = " . $response ;
			return $response;
		}
		
		public function Get($url) {
			//$result = file_get_contents($url);
			/*
			$result ="";
			$handle = @fopen($url, "r");
			if ($handle) {
				while (($buffer = fgets($handle, 10)) !== false) {
					$result =  $result . $buffer;
				}
				if (!feof($handle)) {
					echo "Error: unexpected fgets() fail\n";
				}
				fclose($handle);
			}
			*/
			

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, $url );
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //skip ssl verify
                                                            
			$result = curl_exec($ch);
			curl_close ($ch);

			return $result;
		}
	}


?>