{*
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Login with Google' mod='googlelogin'}</h3>


	<div id="accordion">
		<div class="card">
			<div class="card-header" id="headingsetupsteps">
				<h5 class="mb-0">
					<button class="btn btn-info" data-toggle="collapse" data-target="#collapsesetupsteps"
						aria-expanded="true" aria-controls="collapsesetupsteps">
						{l s='Setup Steps' mod='googlelogin'}
					</button>
				</h5>
			</div>

			<div id="collapsesetupsteps" class="collapse" aria-labelledby="headingsetupsteps" data-parent="#accordion">
				<div class="card-body">
					<ul class="list-group">

						<li class="list-group-item">Go to <a target="_blank" class="button" 
								href="https://console.cloud.google.com/apis/credentials">console.cloud.google.com/apis/credentials&nbsp&nbsp<i
									class="material-icons small">open_in_new</i></a></li>
						<li class="list-group-item">Click <mark>+CREATE CREDENTIALS</mark></li>
						<li class="list-group-item">Click <mark>OAuth client ID</mark></li>
						<li class="list-group-item">Configure <mark>OAuth consent screen</mark>, if system asks you</li>
						<li class="list-group-item">Select <mark>Web application</mark> for <mark>Application
								type</mark></li>
						<li class="list-group-item">Name it. Like: <mark>Google Sign-in for
								{$origin}</mark>&nbsp&nbsp<button data-toggle="pstooltip" data-placement="top"
								title="Copy text" type="button" class="btn btn-default btn-sm"
								onclick="navigator.clipboard.writeText('Google Sign-in for {$origin}')"><i
									class="material-icons small">filter_none</i></button></li>

						<li class="list-group-item">Add URI to <mark>Authorized JavaScript origins</mark>. Enter:
							<mark>{$origin}</mark>&nbsp&nbsp<button data-toggle="pstooltip" data-placement="top"
								title="Copy text" type="button" class="btn btn-default btn-sm"
								onclick="navigator.clipboard.writeText('{$origin}')"><i
									class="material-icons small">filter_none</i></button>
						</li>
						<li class="list-group-item">Add URI to <mark>Authorized redirect URIs</mark>. Enter:
							<mark>{$endpoint}</mark>&nbsp&nbsp<button data-toggle="pstooltip" data-placement="top"
								title="Copy text" type="button" class="btn btn-default btn-sm"
								onclick="navigator.clipboard.writeText('{$endpoint}')"><i
									class="material-icons small">filter_none</i></button>
						</li>
						<li class="list-group-item">Copy new <mark>Client ID</mark> and paste it in field below and
							Click
							<mark>{l s='Save' mod='googlelogin'}</mark>
						</li>
						<li class="list-group-item">Check front Office. Try to login. Check <mark>Advanced Parameters >
								Logs</mark> for
							Errors.
						</li>
					</ul>
				</div>
			</div>
		</div>

	</div>
</div>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Donate if you like this module' mod='googlelogin'}</h3>



	<p>
	<script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="panariga" data-color="#FFDD00" data-emoji="" data-font="Cookie" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff" ></script>
	</p>

</div>