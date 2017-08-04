<?php

namespace Front\Components;

use CMS\Models\Domain\Domain;
use Favez\Mvc\App;

class DomainFinder
{

    /**
     * Tries to find the domain by the requested HTTP_HOST.
     *
     * If found, it returns the model of the domain.
     * If found in the hosts list of a domain, it returns a Response to redirect to.
     * If nothing were found it returns null.
     *
     * @return mixed
     */
    public function find()
    {
        $host = App::request()->getServerParam('HTTP_HOST');

        if (($domain = $this->getDomainByHost($host)) instanceof Domain)
        {
            return $domain;
        }

        if (($domain = $this->getDomainByHosts($host)) instanceof Domain)
        {
            return App::response()->withRedirect($this->getUrl($domain), 301);
        }

        return null;
    }

    private function getUrl(Domain $domain)
    {
        return ($domain->secure ? 'https://' : 'http://') . $domain->host;
    }

    private function getDomainByHosts($host)
    {
        $domains = Domain::findAll();

        /** @var Domain $domain */
        foreach ($domains as $domain)
        {
            $hosts = array_map('trim', explode(PHP_EOL, $domain->hosts));

            if (in_array($host, $hosts))
            {
                return $domain;
            }
        }

        return null;
    }

    private function getDomainByHost($host)
    {
        return Domain::findOneBy(['host' => $host]);
    }

}