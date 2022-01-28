<?php

	use EuVat\EuVat;

	require_once __DIR__ . '/src/EuVat.class.php';

	//Obtaining data from VAT number
	$vatData = EuVat::getVatData('SI', '00000000');
	echo 'Request status: ' . $vatData->getStatus();
	echo '<br>VAT valid: ' . ($vatData->isVatValid() ? 'yes' : 'no');
	echo '<br>Name: ' . $vatData->getName();
	echo '<br>Address: ' . $vatData->getAddress();

	//Simply check for validity of VAT number
	echo 'VAT valid: ' . EuVat::isVatValid('SI', '00000000') ? 'yes' : 'no';
