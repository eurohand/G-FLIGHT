<?php
class MG_SITEMAP {
  private $DATA;
  private $SITEMAP;
  private $XMLDATA;
  private $FILECOUNT = 0;

	//초기화
	public function __construct() {
		//$this->tryWriteMapFile();
	}

	//주소 쿼리 변수
	public function mg_RegisterQueryVars($vars) {
		array_push($vars, 'mgsitemap');
		return $vars;
	}

	//사이트맵 생성
	public function mg_makeSitemap($str){
		if ($str) $mode = explode('-',$str);

		switch($mode[0]){
		  case 'post':
			$this->getPosts('post', $mode[1]);
		  break;

		  case 'goods':
			$this->getPosts('goods', $mode[1]);
		  break;

		  case 'front':
			$this->getFront();
		  break;

		  default:
		  case 'base':
			$this->getBase($mode[1]);
		  break;
		}
	}

	  //정보수집
	private function getBase($mode = ''){
		global $CFG,$TBL;
		DB_Conn();
		$postType = array('post','goods');
		$now=time();
		$addSql="";

		foreach($postType as $ptype){
		  if($ptype=='goods') $addSql=" AND reservation_use='U' ";
		  else $addSql=" AND reservation_use<>'U' ";

		  $result = query("SELECT DISTINCT YEAR(from_unixtime(write_date)) AS `year`, MONTH(from_unixtime(write_date)) AS `month`, MAX(from_unixtime(update_date)) AS last_mod, count(idx) AS posts FROM {$TBL['SP_INFO']} WHERE reservation_display='U' ".$addSql." AND write_date < '{$now}' GROUP BY YEAR(from_unixtime(write_date)), MONTH(from_unixtime(write_date)) ORDER BY write_date DESC");

		  if($result) {
			for($i=0;$data=mysql_fetch_array($result);$i++) {
			  $key = $data['year'].sprintf('%02d',$data['month']);
			  $this->DATA[$ptype][$key]['last_mod'] = $data['last_mod'].PHP_EOL;
			}
		  }
		}

		if ($mode == 'file') $this->buildSitemap('file');
		else $this->buildSitemap('base');
	}

	//포스트,페이지
	private function getPosts($ptype, $needle){
		global $CFG,$TBL;
		DB_Conn();

		$frequency = array('post'=>'weekly', 'page'=>'monthly', 'goods'=>'weekly');
		if(preg_match('/^([0-9]{4})([0-9]{2})$/', $needle, $matches)) {
		  $year  = $matches[1];
		  $month = $matches[2];

		  if($ptype=='goods') $addSql=" AND reservation_use='U' ";
		  else $addSql=" AND reservation_use<>'U' ";

		  $result = query("SELECT * FROM {$TBL['SP_INFO']} WHERE idx<>''".$addSql."AND reservation_display='U' AND YEAR(from_unixtime(write_date))='".$year."' AND MONTH(from_unixtime(write_date))='".$month."' ORDER BY write_date DESC");

		  $this->DATA = array();
		  for($i=0;$data=mysql_fetch_array($result);$i++){
			$this->DATA[$data['idx']]['url'] = $CFG['URL']."/place/detail.html?sp=".$data['idx'];
			$this->DATA[$data['idx']]['date'] = $this->strToGmtDateTime($data['write_date']);
			$this->DATA[$data['idx']]['priority'] = $this->priorityCalc($ptype);
			$this->DATA[$data['idx']]['frequency'] = $frequency[$ptype];
		  }

		  $this->buildSitemap($ptype);
		}
	}


	//프론트
	private function getFront(){
		$this->buildSitemap('front');
	}

	//사이트맵 코드 빌딩
	private function buildSitemap($makeType){
		global $CFG;
		$lastDateTime = time();
		$homeUrl      = $CFG['URL'];

		switch ($makeType){
		  case 'post':
		  case 'goods':
		  {
			$this->SITEMAP = array();
			foreach($this->DATA as $k=>$v){
			  $this->SITEMAP[]  =
				'  <url>'.PHP_EOL
			   .'    <loc>'.$v['url'].'</loc>'.PHP_EOL
			   .'    <lastmod>'.$v['date'].'</lastmod>'.PHP_EOL
			   .'    <changefreq>'.$v['frequency'].'</changefreq>'.PHP_EOL
			   .'    <priority>'.$v['priority'].'</priority>'.PHP_EOL
			   .'  </url>';
			}
		  }
		  break;

		  case 'front':
		  {
			$this->SITEMAP[]  =
			  '  <url>'.PHP_EOL
				 .'    <loc>'.$homeUrl.'</loc>'.PHP_EOL
				 .'    <lastmod>'.$this->strToGmtDateTime($lastDateTime).'</lastmod>'.PHP_EOL
				 .'    <changefreq>daily</changefreq>'.PHP_EOL
				 .'    <priority>1.0</priority>'.PHP_EOL
			   .'  </url>';
		  }
		  break;

		  //default:
	      case 'file':
		  case 'base':
		  {
			if ( $this->FILECOUNT < 1){
			  $sitemapURL  = $homeUrl.'/sitemap.php?mgsitemap=front';
			  $this->SITEMAP[]  =
			   '  <sitemap>'.PHP_EOL
			  .'    <loc>'.$sitemapURL.'</loc>'.PHP_EOL
			  .'    <lastmod>'.$this->strToGmtDateTime($lastDateTime).'</lastmod>'.PHP_EOL
			  .'  </sitemap>';
			  foreach($this->DATA as $type=>$data){
				foreach($data as $k=>$v){
				  $sitemapURL  = $homeUrl.'/sitemap.php?mgsitemap='.$type.'-'.$k;
				  $this->SITEMAP[]  =
				   '  <sitemap>'.PHP_EOL
				  .'    <loc>'.$sitemapURL.'</loc>'.PHP_EOL
				  .'    <lastmod>'.$this->strToGmtDateTime(strtotime($v['last_mod'])).'</lastmod>'.PHP_EOL
				  .'  </sitemap>';
				}
			  }
			  $this->FILECOUNT++;
			}
		  }
		  break;
		}

		$this->finalResult($makeType);
	}

	  //표준시 리턴
	private function strToGmtDateTime($dateTime) {
		return gmstrftime("%Y-%m-%dT%H:%M:%S+00:00", $dateTime);
	}

	//중요도 가중치 산정
	private function priorityCalc($type) {
		$minMax = array('min'=>2, 'max'=>10);
		switch ($type){
		  case 'post':
		  {
			$myRatio = mt_rand($minMax['min'], $minMax['max']);
		  }
		  break;
		  case 'goods':
		  {
			$myRatio = 8;
		  }
		  break;
		}

		return $myRatio <= 2 ? $minMax['min']/10 : $myRatio/10;
	}

	//홈경로
	private function getHomePath() {
		global $CFG;
		$home_path = $CFG['ABSPATH'];
		return $home_path;
	}

	//파일작성시도
	public function tryWriteMapFile() {
		$this->mg_makeSitemap('base-file');
	}

	//결과 표시, 파일로 만들거나 바로보여주거나
	private function finalResult($makeType){
		$this->makeXML($makeType);
		if ($makeType == 'file'){
			if (is_writable($this->getHomePath())){
				$sitemapFP = @fopen($this->getHomePath().'/sitemap.xml', 'w');
				@fwrite($sitemapFP, $this->XMLDATA, mb_strlen($this->XMLDATA));
				@fclose($sitemapFP);
			}
		}else{
			ob_start();
			ob_end_clean();
			header('Content-Type: text/xml; charset=utf-8');
			echo $this->XMLDATA;
			exit;
		}
	}

	//XML 문서 작성
	private function makeXML($makeType){
		$this->XMLDATA = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;

		if ($makeType == 'base' || $makeType == 'file'){
			$this->XMLDATA .= '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

			$this->XMLDATA .= implode(PHP_EOL, $this->SITEMAP).PHP_EOL;
			$this->XMLDATA .= '</sitemapindex>';
		}
		else {
			$this->XMLDATA .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$this->XMLDATA .= implode(PHP_EOL, $this->SITEMAP).PHP_EOL;
			$this->XMLDATA .= '</urlset>';
		}
	}
}
