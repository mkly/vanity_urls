<?php
defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Vanity URLs - A concrete5 Add-On for user profile urls
 *
 * @author Mike Lay
 * @version 0.1
 * @link https://github.com/mkly/vanity_urls
 * @license http://opensource.org/licenses/MIT MIT
 * @copyright Mike Lay 2013
 * @package VanityUrls
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class VanityUrlsPackage extends Package {

	protected $pkgHandle = "vanity_urls";
	protected $appVersionRequired = "5.6";
	protected $pkgVersion = "0.1";

	public function getPackageName() {
		return t('Vanity URLs');
	}

	public function getPackageDescription() {
		return t('Allow users to link to their profile page with a vanity url');
	}
	
	/**
	 * Attaches redirectByUsername to on_start event
	 */
	public function on_start() {
		Events::extend(
			'on_start',
			$this,
			'redirectByUsername',
			__FILE__
		);
	}

	/**
	 * Redirects to a user's profile page
	 */
	public function redirectByUsername() {
		if (!ENABLE_USER_PROFILES) {
			return;
		}

		$path = Request::get()->getRequestPath();
		if (strpos($path, '@') !== 0) {
			return;
		}

		$ui = UserInfo::getByUserName(substr($path, 1));
		if (!$ui) {
			return;
		}

		$controller = new Controller;
		header('HTTP/1.1 301 Moved Permanently');
		$controller->redirect('profile', $ui->getUserID());
	}
}
