<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Configuration;

use Webuntis\Repositories\Repository;
use Webuntis\WebuntisFactory;

/**
 * Class WebuntisConfiguration
 * @package Webuntis
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class WebuntisConfiguration {

    /**
     * WebuntisConfiguration constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        self::setConfig($config);
    }

    /**
     * adds config f.e. another instance to the WebuntisFactry
     * @param array $config
     */
    public static function addConfig(array $config) : void
    {
        $newConfig = array_merge(self::getConfig(), $config);

        WebuntisFactory::setConfig($newConfig);
    }

    /**
     * gets the current config
     * @return array
     */
    public static function getConfig() : array
    {
        return WebuntisFactory::getConfig();
    }

    /**
     * sets the current config in the WebuntisFactory
     * @param array $config
     * @return WebuntisConfiguration $this
     */
    public static function setConfig(array $config) : void
    {
        if(isset($config['disableCache'])) {
            if(extension_loaded('memcached') == false) {
                Repository::$disabledCache = true;
            }else {
                Repository::$disabledCache = $config['disableCache'];
            }
        } else {
            if(extension_loaded('memcached') == false) {
                Repository::$disabledCache = true;
            }else {
                Repository::$disabledCache = false;
            }
        }
        WebuntisFactory::setConfig($config);
    }
}