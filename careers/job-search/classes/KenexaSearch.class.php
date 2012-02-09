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
	
	// String representation of questions XML.
    // Built up from the addQuestion function.
    protected $questions = "";

    public $fields;
    
    public function __construct($fields = null) {
      $this->fields = $fields;
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

    /*
      Returns an array of available search fields from the big XML file
      including input types and select options where appropriate.
      Makes for nice JSON output if converted using json_encode().
      Typical field output in JSON would look like this:
      {
      "11512": {
      "Name": "Position Type",
      "Type": "radio",
      "options": [{
      "Code": "Full Time",
      "Description": "Full Time"
      }, {
      "Code": "Part Time",
      "Description": "Part Time"
      }, {
      "Code": "Intern",
      "Description": "Intern"
      }, {
      "Code": "Temporary",
      "Description": "Temporary"
      }, {
      "Code": "Fixed Term",
      "Description": "Fixed Term"
      }]
      }...
      }
     */

    public function getFields() {
/*        $bigXML =
                <<<XML
<?xml version="1.0"?><Envelope version="01.00"><Sender><Id></Id><Credential>391</Credential></Sender><Recipient><Id /></Recipient><TransactInfo transactType="data"><transactId></transactId><timeStamp></timeStamp></TransactInfo><Packet><PacketInfo packetType="data"><PacketId>1</PacketId><Action /><Manifest /><PacketCode /></PacketInfo><Payload><ResultSet><![CDATA[<Field Qid='1136'><Name>Department</Name><Type>select</Type><Options><Option><Code>ATW</Code><Description>TimeWarner Corporate</Description></Option><Option><Code>HBO</Code><Description>HBO</Description></Option><Option><Code>NLC</Code><Description>New Line Cinema</Description></Option><Option><Code>TBS</Code><Description>Turner Broadcasting</Description></Option><Option><Code>TIC</Code><Description>Time Inc.</Description></Option><Option><Code>Warner Bros. Entertainment Group</Code><Description>Warner Bros. Entertainment Group</Description></Option><Option><Code>DC Comics</Code><Description>DC Comics</Description></Option><Option><Code>IPC</Code><Description>IPC Media</Description></Option><Option><Code>CWN</Code><Description>The CW Network</Description></Option></Options></Field><Field Qid='1142'><Name>Job Description</Name><Type>textarea</Type></Field><Field Qid='1158'><Name>Keyword</Name><Type>Text</Type></Field><Field Qid='1140'><Name>Location/Division</Name><Type>select</Type><Options><Option><Code>Alabama - Birmingham - United States</Code><Description>United States - Alabama - Birmingham</Description></Option><Option><Code>California - Beverly Hills - United States</Code><Description>United States - California - Beverly Hills</Description></Option><Option><Code>California - Burbank - United States</Code><Description>United States - California - Burbank</Description></Option><Option><Code>California - Glendale - United States</Code><Description>United States - California - Glendale</Description></Option><Option><Code>California - Los Angeles - United States</Code><Description>United States - California - Los Angeles</Description></Option><Option><Code>California - Menlo Park - United States</Code><Description>United States - California - Menlo Park</Description></Option><Option><Code>California - San Diego - United States</Code><Description>United States - California - San Diego</Description></Option><Option><Code>California - San Francisco - United States</Code><Description>United States - California - San Francisco</Description></Option><Option><Code>California - Santa Monica - United States</Code><Description>United States - California - Santa Monica</Description></Option><Option><Code>California - Sherman Oaks - United States</Code><Description>United States - California - Sherman Oaks</Description></Option><Option><Code>Connecticut - Stamford - United States</Code><Description>United States - Connecticut - Stamford</Description></Option><Option><Code>District of Columbia - Washington - United States</Code><Description>United States - District of Columbia - Washington</Description></Option><Option><Code>Florida - Miami - United States</Code><Description>United States - Florida - Miami</Description></Option><Option><Code>Florida - Ocala - United States</Code><Description>United States - Florida - Ocala</Description></Option><Option><Code>Florida - Tampa - United States</Code><Description>United States - Florida - Tampa</Description></Option><Option><Code>Georgia - Atlanta - United States</Code><Description>United States - Georgia - Atlanta</Description></Option><Option><Code>Illinois - Chicago - United States</Code><Description>United States - Illinois - Chicago</Description></Option><Option><Code>Massachusetts - Boston - United States</Code><Description>United States - Massachusetts - Boston</Description></Option><Option><Code>Massachusetts - Cambridge - United States</Code><Description>United States - Massachusetts - Cambridge</Description></Option><Option><Code>Michigan - Detroit - United States</Code><Description>United States - Michigan - Detroit</Description></Option><Option><Code>Minnesota - Minneapolis - United States</Code><Description>United States - Minnesota - Minneapolis</Description></Option><Option><Code>Missouri - St. Louis - United States</Code><Description>United States - Missouri - St. Louis</Description></Option><Option><Code>New York - Hauppauge - United States</Code><Description>United States - New York - Hauppauge</Description></Option><Option><Code>New York - New York - United States</Code><Description>United States - New York - New York</Description></Option><Option><Code>North Carolina - Charlotte - United States</Code><Description>United States - North Carolina - Charlotte</Description></Option><Option><Code>Ohio - Cincinnati - United States</Code><Description>United States - Ohio - Cincinnati</Description></Option><Option><Code>Ohio - Columbus - United States</Code><Description>United States - Ohio - Columbus</Description></Option><Option><Code>Oklahoma - Oklahoma City - United States</Code><Description>United States - Oklahoma - Oklahoma City</Description></Option><Option><Code>Pennsylvania - Philadelphia - United States</Code><Description>United States - Pennsylvania - Philadelphia</Description></Option><Option><Code>Pennsylvania - Pittsburgh - United States</Code><Description>United States - Pennsylvania - Pittsburgh</Description></Option><Option><Code>Tennessee - Nashville - United States</Code><Description>United States - Tennessee - Nashville</Description></Option><Option><Code>Texas - Austin - United States</Code><Description>United States - Texas - Austin</Description></Option><Option><Code>Texas - Dallas - United States</Code><Description>United States - Texas - Dallas</Description></Option><Option><Code>Virginia - Dulles - United States</Code><Description>United States - Virginia - Dulles</Description></Option><Option><Code>Washington - Seattle - United States</Code><Description>United States - Washington - Seattle</Description></Option><Option><Code>London - United Kingdom</Code><Description>United Kingdom - London</Description></Option><Option><Code>Madrid - Spain</Code><Description>Spain - Madrid</Description></Option><Option><Code>Hong Kong - Hong Kong</Code><Description>Hong Kong - Hong Kong</Description></Option><Option><Code>Hamburg - Germany</Code><Description>Germany - Hamburg</Description></Option><Option><Code>Munich - Germany</Code><Description>Germany - Munich</Description></Option><Option><Code>Paris - France</Code><Description>France - Paris</Description></Option><Option><Code>Ontario - Toronto - Canada</Code><Description>Canada - Ontario - Toronto</Description></Option><Option><Code>Michigan - Troy - United States</Code><Description>United States - Michigan - Troy</Description></Option><Option><Code>Berlin – Germany</Code><Description>Germany - Berlin</Description></Option><Option><Code>Rome – Italy</Code><Description>Italy - Rome</Description></Option><Option><Code>Dusseldorf – Germany</Code><Description>Germany - Dusseldorf</Description></Option><Option><Code>Florida - Coral Gables - United States</Code><Description>United States - Florida - Coral Gables</Description></Option><Option><Code>Massachusetts - Concord - United States</Code><Description>United States - Massachusetts - Concord</Description></Option><Option><Code>The Netherlands - Amsterdam</Code><Description>Netherlands - Amsterdam</Description></Option><Option><Code>New Jersey - Parsippany - United States</Code><Description>United States - New Jersey - Parsippany</Description></Option><Option><Code>California - Century City - United States</Code><Description>United States - California - Century City</Description></Option><Option><Code>Quebec - Montreal - Canada</Code><Description>Canada - Quebec - Montreal</Description></Option><Option><Code>Abu Dhabi - United Arab Emirates</Code><Description>United Arab Emirates - Abu Dhabi</Description></Option><Option><Code>Washington - Kirkland - United States</Code><Description>United States - Washington - Kirkland</Description></Option><Option><Code>Washington - Bothell - United States</Code><Description>United States - Washington - Bothell</Description></Option><Option><Code>New Jersey - Trenton - United States</Code><Description>United States - New Jersey - Trenton</Description></Option><Option><Code>Arkansas - Rogers - United States</Code><Description>United States - Arkansas - Rogers</Description></Option><Option><Code>Belgium - Antwerp</Code><Description>Belgium - Antwerp</Description></Option><Option><Code>British Columbia - Vancouver - Canada</Code><Description>Canada - British Columbia - Vancouver</Description></Option><Option><Code>Massachusetts - Westwood - United States</Code><Description>United States - Massachusetts - Westwood</Description></Option></Options></Field><Field Qid='1135'><Name>Title</Name><Type>text</Type></Field><Field Qid='11524'><Name>Area of <BR>Interest</Name><Type>multi-select</Type><Options><Option><Code>Administrative/Clerical</Code><Description>Administrative/Clerical</Description></Option><Option><Code>Business Affairs/Development/Analysis</Code><Description>Business Affairs/Development/Analysis</Description></Option><Option><Code>Content Development/Content Programming</Code><Description>Content Development/Content Programming</Description></Option><Option><Code>Customer Service/Member Services</Code><Description>Customer Service/Member Services</Description></Option><Option><Code>Facilities/Security</Code><Description>Facilities/Security</Description></Option><Option><Code>Finance/Accounting</Code><Description>Finance/Accounting</Description></Option><Option><Code>Graphics/Design</Code><Description>Graphics/Design</Description></Option><Option><Code>Human Resources</Code><Description>Human Resources</Description></Option><Option><Code>Information Technology Services</Code><Description>Information Technology Services</Description></Option><Option><Code>Legal</Code><Description>Legal</Description></Option><Option><Code>Marketing</Code><Description>Marketing</Description></Option><Option><Code>Product Management</Code><Description>Product Management</Description></Option><Option><Code>Project/Program Management</Code><Description>Project/Program Management</Description></Option><Option><Code>Sales</Code><Description>Sales</Description></Option><Option><Code>Ad Sales</Code><Description>Ad Sales</Description></Option><Option><Code>Animation</Code><Description>Animation</Description></Option><Option><Code>Book Publishing</Code><Description>Book Publishing</Description></Option><Option><Code>Construction</Code><Description>Construction</Description></Option><Option><Code>Corp Communications/Corp Affairs/Government Affairs</Code><Description>Corp Communications/Corp Affairs/Government Affairs</Description></Option><Option><Code>Creative</Code><Description>Creative</Description></Option><Option><Code>Editorial</Code><Description>Editorial</Description></Option><Option><Code>Engineering/Technical Operations</Code><Description>Engineering/Technical Operations</Description></Option><Option><Code>Executive Management</Code><Description>Executive Management</Description></Option><Option><Code>Internet/Online/New Media</Code><Description>Internet/Online/New Media</Description></Option><Option><Code>Internship/Trainee</Code><Description>Internship/Trainee</Description></Option><Option><Code>Operations/General</Code><Description>Operations/General</Description></Option><Option><Code>Other</Code><Description>Other</Description></Option><Option><Code>Procurement/Purchasing</Code><Description>Procurement/Purchasing</Description></Option><Option><Code>Production</Code><Description>Production</Description></Option><Option><Code>Promotions/Advertising</Code><Description>Promotions/Advertising</Description></Option><Option><Code>Retail/Merchandising/Store Operations</Code><Description>Retail/Merchandising/Store Operations</Description></Option><Option><Code>Strategic Planning</Code><Description>Strategic Planning</Description></Option><Option><Code>Telecommunications</Code><Description>Telecommunications</Description></Option><Option><Code>Television/Programming</Code><Description>Television/Programming</Description></Option><Option><Code>Public Relations/Publicity</Code><Description>Public Relations/Publicity</Description></Option><Option><Code>Direct Marketing</Code><Description>Direct Marketing</Description></Option><Option><Code>VOIP/Telephony</Code><Description>VOIP/Telephony</Description></Option><Option><Code>Interactive Entertainment</Code><Description>Interactive Entertainment</Description></Option><Option><Code>Market/Media Research</Code><Description>Market/Media Research</Description></Option><Option><Code>Network Television Production</Code><Description>Network Television Production</Description></Option><Option><Code>Cable Television Production</Code><Description>Cable Television Production</Description></Option><Option><Code>Interactive Marketing</Code><Description>Interactive Marketing</Description></Option><Option><Code>Recruiting</Code><Description>Recruiting</Description></Option><Option><Code>Magazine Publishing</Code><Description>Magazine Publishing</Description></Option><Option><Code>Sales and Distribution</Code><Description>Sales and Distribution</Description></Option><Option><Code>Anti-Piracy</Code><Description>Anti-Piracy</Description></Option><Option><Code>Real Estate</Code><Description>Real Estate</Description></Option><Option><Code>News</Code><Description>News</Description></Option><Option><Code>Sports</Code><Description>Sports</Description></Option><Option><Code>International</Code><Description>International</Description></Option><Option><Code>Government/Corporate Affairs</Code><Description>Government/Corporate Affairs</Description></Option><Option><Code>Research and Development</Code><Description>Research and Development</Description></Option><Option><Code>Ad Buying and Inventory</Code><Description>Ad Buying and Inventory</Description></Option><Option><Code>Librarian Science</Code><Description>Librarian Science</Description></Option></Options></Field><Field Qid='12234'><Name>Business Unit</Name><Type>text</Type></Field><Field Qid='23702'><Name>Industry</Name><Type>multi-select</Type><Options><Option><Code>Advertising</Code><Description>Advertising</Description></Option><Option><Code>Cable/Broadcast Television Networks</Code><Description>Cable/Broadcast Television Networks</Description></Option><Option><Code>Corporate Media</Code><Description>Corporate Media</Description></Option><Option><Code>Film Production and Distribution</Code><Description>Film Production and Distribution</Description></Option><Option><Code>Television Program Production and Distribution</Code><Description>Television Program Production and Distribution</Description></Option><Option><Code>Publishing</Code><Description>Publishing</Description></Option><Option><Code>Online Content/Services</Code><Description>Online Content/Services</Description></Option></Options></Field><Field Qid='11512'><Name>Position <BR>Type</Name><Type>radio</Type><Options><Option><Code>Full Time</Code><Description>Full Time</Description></Option><Option><Code>Part Time</Code><Description>Part Time</Description></Option><Option><Code>Intern</Code><Description>Intern</Description></Option><Option><Code>Temporary</Code><Description>Temporary</Description></Option><Option><Code>Fixed Term</Code><Description>Fixed Term</Description></Option></Options></Field>]]></ResultSet></Payload></Packet></Envelope>
XML;
        $xml = simplexml_load_string($bigXML);
        // Extract the result set data, and convert to a valid xml string.
        $xmlStr = '<?xml version="1.0" encoding="UTF-8"?><data>' . $xml->Packet->Payload->ResultSet . '</data>';
        // We have some stray <BR> html tags in the data, so remove those.
        $xmlStr = str_replace("<BR>", "", $xmlStr);
        // Create the xml object.
        $xml = simplexml_load_string($xmlStr);
        $fieldsOut = array();
        foreach ($xml as $key => $field) {
            $obj = array();
            $obj['Name'] = (String) $field->Name;
            $obj['Type'] = (String) $field->Type;
            $obj['options'] = array();
            foreach ($field->Options as $option) {
                foreach ($option as $opt) {
                    array_push($obj['options'], get_object_vars($opt));
                }
            }
            // Save the Qid as the associative index for each field.
            $fieldsOut[(String) $field->attributes()->Qid] = $obj;
        }*/
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
