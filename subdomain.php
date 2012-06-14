<?php

function create_subdomain($subDomain, $cPanelUser, $cPanelPass, $rootDomain) {

//	$buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain;

	$buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/".$subDomain;

	$openSocket = fsockopen('localhost',2082);
	if(!$openSocket) {
		return "Socket error";
		exit();
	}

	$authString = $cPanelUser . ":" . $cPanelPass;
	$authPass = base64_encode($authString);
	$buildHeaders  = "GET " . $buildRequest ."\r\n";
	$buildHeaders .= "HTTP/1.0\r\n";
	$buildHeaders .= "Host:localhost\r\n";
	$buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
	$buildHeaders .= "\r\n";

	fputs($openSocket, $buildHeaders);
	while(!feof($openSocket)) {
	fgets($openSocket,128);
	}
	fclose($openSocket);

	$newDomain = "http://" . $subDomain . "." . $rootDomain . "/";

	return "Created subdomain $newDomain";

}

function delete_subdomain($subDomain,$cPanelUser,$cPanelPass,$rootDomain)
{
	$buildRequest = "/frontend/x3/subdomain/dodeldomain.html?domain=" . $subDomain . "_" . $rootDomain;

	$openSocket = fsockopen('localhost',2082);
	if(!$openSocket) {
		return "Socket error";
		exit();
	}

	$authString = $cPanelUser . ":" . $cPanelPass;
	$authPass = base64_encode($authString);
	$buildHeaders  = "GET " . $buildRequest ."\r\n";
	$buildHeaders .= "HTTP/1.0\r\n";
	$buildHeaders .= "Host:localhost\r\n";
	$buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
	$buildHeaders .= "\r\n";

	fputs($openSocket, $buildHeaders);
	while(!feof($openSocket)) {
	fgets($openSocket,128);
	}
	fclose($openSocket);
	
	$passToShell = "rm -rf /home/" . $cPanelUser . "/public_html/" . $subDomain;
	system($passToShell);
}
?>