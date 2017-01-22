<?php

namespace Envify\Service;

class RatioService
{

    public function __construct($parameters, $client)
    {
        $this->parameters = $parameters;
        $this->client = $client;
    }

    public function getLocationWeight()
    {
        try {
            /*
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/ratios'
            );
            $res = $res->getBody()->getContents()
            */
            $res = '{"cuenca":71.79,"ciudad_real":69.29,"ourense":66.55,"albacete":64.9,"jaen":64.6,"zamora":63.56,"badajoz":63.3,"guadalajara":61.87,"palencia":59.67,"lugo":57.13,"leon":56.85,"toledo":55.99,"pontevedra":55.25,"lleida":54.6867,"caceres":53.66,"avila":53.58,"valladolid":52.76,"teruel":52.24,"coruña":51.74,"castellon":51.58,"asturias":49.74,"alava":47.73,"soria":47.56,"burgos":47.29,"zaragoza":45.81,"cordoba":45.36,"navarra":45.11,"segovia":44.67,"melilla":44.35,"murcia":43.65,"valencia":42.25,"huelva":42.23,"la_rioja":42.03,"cantabria":41.41,"almeria":40.2,"bizkaia":39.7,"cadiz":38.74,"salamanca":38.44,"granada":37.87,"huesca":37.86,"sevilla":36.78,"gipuzkoa":35.3,"madrid":34.59,"tarragona":31.91,"ceuta":31.36,"islas_baleares":30.13,"barcelona":29.8933,"malaga":26.33,"alicante":20.12,"girona":17.69,"las_palmas":14.1766,"tenerife":13.86}';
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with Ratios Service');
        }

        return json_decode($res, true);
    }

    /**
     * Get CategoryID by Name
     */
    public function getCategoryIdByName($name)
    {
        try {
            $res = $this->client->request(
                'GET',
                $this->parameters['endpoint'] . '/subcategories?lang=' . $this->lang . '&api_key=' . $this->parameters['apiKey']
            );
        } catch (\Exception $e) {
            throw new \Exception('Impossible to connect with MiNube');
        }

        $categories = json_decode($res->getBody()->getContents());
        $result = new \stdClass();

        foreach ($categories as $category) {
            if ($category->name == $name) {
                $result = $category;
                break;
            }
        }

        return $result;
    }
}
