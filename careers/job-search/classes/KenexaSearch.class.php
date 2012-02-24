<?php

/*
 * Wrapper class for the Kenexa job search api.
 */

class KenexaSearch {

    public static $searchUrl = "http://import.brassring.com/WebRouter/WebRouter.asmx?wsdl";
    // Our base request XML.
    public static $soapRequest =
            '<?xml version="1.0" encoding="UTF-8"?>
        <Envelope version="01.00">
            <Sender>
            <Id>TWWEBAPIUSER</Id>
            <Credential>391</Credential>
            </Sender>
            <TransactInfo transactId="1" transactType="data">
            <TransactId>9/1/2010</TransactId>
            <TimeStamp>12:00:00 AM</TimeStamp>
            </TransactInfo>
            <Unit UnitProcessor="SearchAPI">
            <Packet>
                <PacketInfo packetType="data">
                <packetId>1</packetId>
                </PacketInfo>
                <Payload>
                <InputString>
                    <ClientId>391</ClientId>
                    <SiteId>36</SiteId>
                    <PageNumber></PageNumber>
                    <OutputXMLFormat>0</OutputXMLFormat>
                    <AuthenticationToken></AuthenticationToken>
                    <HotJobs></HotJobs>
                    <JobDescription>YES</JobDescription>
                    <ProximitySearch>
                    <Distance/>
                    <Measurement/>
                    <Country/>
                    <State/>
                    <City/>
                    <zipCode/>
                    </ProximitySearch>
					<DatePosted/>
                    <JobMatchCriteriaText/>
                    <SelectedSearchLocaleId/>
                    <Questions>
                    </Questions>
                </InputString>
                </Payload>
            </Packet>
            </Unit>
        </Envelope>';
    // Ouput from the scraper, hard-coded for now.
    public static $scraperOutput =
            '<options>
            <Departments questionID="1136__Department" questionNumber="1136"/>
            <Locations questionID="1140__Location" questionNumber="1140"/>
            <Industries questionID="23702__FORMTEXT7" questionNumber="23702"/>
            <Interests questionID="11524__FormText3" questionNumber="11524"/>
            <Types questionID="11512__FormText2" questionNumber="11512"/>
        </options>';

	/**
	 * The search page number
	 * @var int
	 */
	public $pageNumber = 1;
	
	/**
	 * The date to search from (yyyy-mm-dd)
	 * @var string
	 */
	public $datePosted = "All";

	/**
	 * Search direction ASC or DESC
	 * @var string
	 */
	public $sortDir = "DESC";

	/**
	 * Hot Jobs search true or false
	 * @var boolean
	 */
    public $hotJobs = false;
    	
	// String representation of questions XML.
    // Built up from the addQuestion function.
    protected $questions = "";



    
    public $fields;
    
    public function __construct($optionsXML = null) {
      if($optionsXML){  
	    $options = simplexml_load_string(file_get_contents($optionsXML));
	    $json = json_encode($options);
	    $options = json_decode($json,TRUE);
	    $fields = array();
	    foreach($options as $name => $option){
	  	  $obj = array();
	  	  $obj['Name'] = $name;
		  $obj['Type'] = $option['@attributes']['type'];
		  $obj['options'] = array();
		  if(isset($option['Options'])){
		  	foreach ($option['Options'] as $o) {
			    $opt = array();		   
			    $val = split("\|",$o);
			    $desc = split('=',$val[1]);		    
			    $opt['Description'] = $desc[0];
			    $val = split("\|",$o);
			    $code = split('=',$val[3]);		    
			    $opt['Code'] = $code[0];
			    $obj['options'][]  = $opt;
			}
	  	  }
		  $fields[$option['@attributes']['questionNumber']] = $obj;
	    }
  

        $this->fields = $fields;
      }
    }

    /**	 
	 * Returns an array of option names and question numbers.
	 * @return array
	 */
    public function getOptions() {
        $options = array();
        $xml = simplexml_load_string(self::$scraperOutput);
        foreach ($xml as $key => $option) {
            $options[$key] = $option->attributes()->questionNumber;
        }
        return $options;
    }

    public function getFields() {
        return $this->fields;
    }

	/**
	 * Creates a cross reference structure from the location data so we can
	 * create separate inputs for country, state and city.
	 * Output table cross references as follows: countries => states => cities.
	 * Location data is given to us in the following format (from the Description field):
	 * "country - state - city" *OR* "country - city" 
	 * @return array
	 */
	public function getXRefLocationData() {
		// Get the data in a friendly format.
		$data = $this->getFields();
		// Location info is at index 1140.
		$locations = $data['1140']['options'];		
		$output = array();
		foreach($locations as $location){
			// Split into separate country(0), state(1), and city(2) parts.
			$components = explode(' - ',$location['Description']);
			// If only 2 components, then we only have country and city, so adjust.
			if (count($components) == 2) {
				$components[2] = $components[1];
				// Effectively, we are creating an imaginary state called NO_STATE
				// that has the stateless city in it.
				$components[1] = 'NO_STATE';
			}
			// Use country as index to an array.
			if (!isset($output[$components[0]])) {
				$output[$components[0]] = array();
			}
			// Use state as index to an array stored in country array.
			if (!isset($output[$components[0]][$components[1]])) {
				$output[$components[0]][$components[1]] = array();
			}
			// Store city in state array.
			array_push($output[$components[0]][$components[1]],$components[2]);			
		}
		return $output;		
	}
	
    // Dump the scraper xml.
    public function showScraper() {
        $xml = simplexml_load_string(self::$scraperOutput);
        var_dump($xml);
    }

    // Adds a question to the search.
    public function addQuestion($questionId, $searchTerm, $sortFlag=false) {	
		$sort = 'Sortorder="'.$this->sortDir.'" Sort="No"';
		//$sort="";
		if($sortFlag) {
			$sort = 'Sortorder="'.$this->sortDir.'" Sort="Yes"';				
		}
		$this->questions .=
                "<Question $sort>" .
					"<Id>$questionId</Id>" .
					"<Value>$searchTerm</Value>" .
                '</Question>';
    }

    // Performs the search based on the added questions.
    // Returns the xml data, or null if no questions specified.
    public function search() {
        if ($this->questions == "")
            return null;
        $kenexa = new SoapClient(self::$searchUrl);
        $soapRequest = self::$soapRequest;

        // Insert the questions into the request.
        $soapRequest = str_replace("<Questions>", "<Questions>" . $this->questions, $soapRequest);
		$soapRequest = str_replace("<PageNumber>", "<PageNumber>" . $this->pageNumber, $soapRequest);
		if($this->hotJobs){
  		  $soapRequest = str_replace("<HotJobs>", "<HotJobs>Yes", $soapRequest);
		}
		if($this->datePosted != "All") $soapRequest = str_replace("<DatePosted/>", "<DatePosted><Date>" . $this->datePosted."</Date></DatePosted>", $soapRequest);
		$soapRequest = str_replace("<DatePosted/>", "" , $soapRequest);

        $result = $kenexa->route(array('inputXml' => $soapRequest));
        $xml = simplexml_load_string($result->routeResult);
        return $xml->Unit->Packet->Payload->ResultSet;
    }

}

// Enums for the returned "Question" array.
Class KenexaJobData {

    const JOB_TITLE = 0;        // E.g. Senior Researcher 
    const LOCATION = 1;         // E.g. United States - California - Burbank 
    const DIVISION = 2;         // E.g. Warner Bros. Entertainment Group
    const INDUSTRY = 3;         // E.g. Business Affairs/Development/Analysis, Legal
    const POSITION_TYPE = 4;    // E.g. Full Time, Intern etc.
    const REQUISITION_NO = 5;   // E.g. 128533BR

}

// Enums for questions.
Class KenexaJobQuestions {

    const DIVISION = 1136;          // Single select
    const DESCRIPTION = 1142;       // Text
    const LOCATION = 1140;          // 
    const TITLE = 1135;             // Text
    const AREA_OF_INTEREST = 11524; // Multi select
    const INDUSTRY = 23702;         // Multi select
    const POSITION_TYPE = 11512;    // Single select
    const KEYWORD = 1158;           // Text
    const BUSINESS_UNIT = 12234;    //
    
    public static function getQuestionsHash() {
     return array(
        "division" => KenexaJobQuestions::DIVISION,
        "description" => KenexaJobQuestions::DESCRIPTION,
        "location" => KenexaJobQuestions::LOCATION,
        "title" => KenexaJobQuestions::TITLE,
        "area_of_interest" =>  KenexaJobQuestions::AREA_OF_INTEREST,
        "industry" => KenexaJobQuestions::INDUSTRY,
        "position" => KenexaJobQuestions::POSITION_TYPE,
        "keyword" => KenexaJobQuestions::KEYWORD,
        "business_unit" => KenexaJobQuestions::BUSINESS_UNIT
      );
    }
    
}

?>
