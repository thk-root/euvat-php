<?php

	namespace EuVat;

	use SoapClient;
	use Throwable;

	/**
	 * EuVat
	 *
	 * Main static class, used to obtain the response for a given VAT number.
	 *
	 * @package EuVat
	 * @author THK <tilen@thk.si>
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class EuVat {


		/**
		 * @var string API url
		 */
		const API_URL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';


		/**
		 * @var array stored results, obtained in the existing lifecycle
		 */
		private static $cached = [];


		/**
		 * @var null|SoapClient Soap client instance for singleton purposes
		 */
		private static $soapClientInstance = null;


		/**
		 * Obtains complete data structure for a given country ISO + VAT number
		 *
		 * @param string $countryIso 2-letter country ISO
		 * @param string $vat VAT number to check
		 *
		 * @return Result
		 */
		public static function getVatData(string $countryIso, string $vat) : Result {

			$r = new Result();
			$countryIso = strtoupper($countryIso);
			$vat = trim($vat);
			$requestKey = $countryIso . $vat;

			//Validate country ISO length
			if(strlen($countryIso) !== 2) return $r->setFailed('Invalid 2-letter country ISO')->storeInCache($requestKey, self::$cached);

			try {
				if(self::$soapClientInstance === null) self::$soapClientInstance = new SoapClient(self::API_URL);
			} catch(Throwable $e) {
				return $r->setFailed('Connection failed: ' . $e->getMessage());
			}

			//Cached result already exists
			if(isset(self::$cached[$requestKey])) return self::$cached[$requestKey];

			try {

				//Obtain response from the API
				$response = self::$soapClientInstance->checkVat([
					'countryCode' => $countryIso,
					'vatNumber' => $vat
				]);

			} catch(Throwable $e) {
				return $r->setFailed($e->getMessage())->storeInCache($requestKey, self::$cached);
			}

			//Store and return successful response
			return $r->setResponseOk($response->valid, $response->name, $response->address)->storeInCache($requestKey, self::$cached);
		}


		/**
		 * Obtains simple boolean, indicating validity of the given VAT number
		 *
		 * @param string $countryIso 2-letter country ISO
		 * @param string $vat VAT number to check
		 *
		 * @return bool
		 */
		public static function isVatValid(string $countryIso, string $vat) : bool {
			return self::getVatData($countryIso, $vat)->isVatValid();
		}

	}


	/**
	 * Result
	 *
	 * Object, containing response from VIES.
	 *
	 * @package EuVat
	 * @author THK <tilen@thk.si>
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class Result {

		const STATUS_OK = 'ok';
		const STATUS_FAIL = 'fail';

		private $status;
		private $statusMsg;
		private $isValid;
		private $name;
		private $address;


		/**
		 * Sets OK response
		 *
		 * @param bool $isValid
		 * @param string|null $name
		 * @param string|null $address
		 *
		 * @return Result
		 */
		public function setResponseOk(bool $isValid, ?string $name, ?string $address) : Result {
			$this->status = self::STATUS_OK;
			$this->isValid = $isValid;
			$this->name = $name;
			$this->address = $address;
			return $this;
		}


		/**
		 * Sets failed response
		 *
		 * @return $this
		 */
		public function setFailed(string $message) : Result {
			$this->status = self::STATUS_FAIL;
			$this->statusMsg = $message;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function isResponseOk() : bool {
			return $this->status === self::STATUS_OK;
		}


		/**
		 * @return string|null
		 */
		public function getStatus() : ?string {
			return $this->status;
		}


		/**
		 * @return string|null
		 */
		public function getStatusMessage() : ?string {
			return $this->statusMsg;
		}


		/**
		 * @return bool
		 */
		public function isVatValid() : bool {
			return $this->isValid;
		}


		/**
		 * @return string|null
		 */
		public function getName() : ?string {
			return $this->name;
		}


		/**
		 * @return string|null
		 */
		public function getAddress() : ?string {
			return $this->address;
		}


		/**
		 * @return $this
		 */
		public function storeInCache(string $key, array &$cache) : Result {
			$cache[$key] = $this;
			return $this;
		}
	}
