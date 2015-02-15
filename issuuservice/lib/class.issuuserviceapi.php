<?php

/**
*   Classe IssuuServiceAPI
*
*   @author Pedro Marcelo de Sá Alves
*   @link https://github.com/pedromarcelojava/
*   @version 1.0.3
*/
abstract class IssuuServiceAPI
{

    /**
    *   Chave de API da aplicação
    *
    *   @access private
    *   @var string
    */
    private $api_key;

    /**
    *   Chave secreta de API da aplicação
    *
    *   @access private
    *   @var string
    */
    private $api_secret;

    /**
    *   URL da API do Issuu
    *
    *   @access private
    *   @var string
    */
    private $api_url = 'http://api.issuu.com/1_0';

    /**
    *   URL de upload do Issuu
    *
    *   @access private
    *   @var string
    */
    private $upload_url = 'http://upload.issuu.com/1_0';

    /**
    *   Parâmetros da requisição em forma de array
    *
    *   @access protected
    *   @var array
    */
    protected $params;

    /**
    *   Parâmetros da requisição em forma de string
    *
    *   @access protected
    *   @var string
    */
    protected $params_str;

    /**
    *   Assinatura calculada
    *
    *   @access protected
    *   @var string
    */
    protected $signature;

    /**
    *   Nome do método list
    *
    *   @access protected
    *   @var string
    */
    protected $list;

    /**
    *   Nome do método delete
    *
    *   @access protected
    *   @var string
    */
    protected $delete;

    /**
    *   Slug da seção
    *
    *   @access protected
    *   @var string
    */
    protected $slug_section;

    /**
    *   IssuuServiceAPI::__construct()
    *
    *   Construtor da classe
    *
    *   @access public
    *   @param string $api_key Correspondente a chave de API da aplicação
    *   @param string $api_secret Correspondente a chave secreta de API da aplicação
    *   @throws Exception Lança uma exceção caso não seja informada a chave de API ou API secreta
    */
    public function __construct($api_key, $api_secret)
    {
        if (is_string($api_key) && strlen($api_key) >= 1)
        {
            if (is_string($api_secret) && strlen($api_secret) >= 1)
            {
                $this->api_key = $api_key;
                $this->api_secret = $api_secret;
            }
            else
            {
                throw new Exception('A API secreta não é uma String ou está vazia');
            }
        }
        else
        {
            throw new Exception('A chave de API não é uma String ou está vazia');
        }
    }

    /**
    *   IssuuServiceAPI::__destruct()
    *
    *   Desconstrutor da classe
    *
    *   @access public
    */
    public function __destruct()
    {
        return false;
    }

    /**
    *   IssuuServiceAPI::getSignature()
    *
    *   Método acessor da variável $signature
    *
    *   @access public
    *   @return string Assinatura que será passada por parâmetro
    */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
    *   IssuuServiceAPI::buildUrl()
    *
    *   Monta a URL da requisição
    *
    *   @access protected
    *   @param boolean $is_api_url
    *   @return string Retorna a URL da api ou upload junto com os parâmetros passados
    */
    protected function buildUrl($is_api_url = true)
    {
        if ($is_api_url == true)
        {
            // echo $this->api_url . '?' . $this->params_str . '<br>';
            return $this->api_url . '?' . $this->params_str;
        }
        else if ($is_api_url == false)
        {
            return $this->upload_url . '?' . $this->params_str;
        }
        else
        {
            return false;
        }
    }

    /**
    *   IssuuServiceAPI::setParams()
    *
    *   Seta os parâmetros da requisição
    *
    *   @access protected
    *   @param array $params
    *   @throws Exception Lança um exceção caso não tenha parâmetros
    */
    protected function setParams($params)
    {
        if (is_array($params) && !empty($params))
        {
            $this->params = $params;
            $this->params['apiKey'] = $this->api_key;
            $this->signature = $this->calculateSignature();
            $this->params['signature'] = $this->signature;
            $this->params_str = $this->params_str . '&signature=' . $this->signature;
        }
        else
        {
            throw new Exception('Os parâmetros não é um array ou está vazio');
        }
    }

    /**
    *   IssuuServiceAPI::calculateSignature()
    *
    *   Faz o cálculo da assinatura
    *
    *   @access protected
    *   @return string A assinatura
    */
    final protected function calculateSignature()
    {
        if (ksort($this->params))
        {
            $this->params_str = http_build_query($this->params);
            $this->params_str = rawurldecode($this->params_str);
            $sign_str = strtr($this->params_str, array('&' => '', '=' => '', '+' => ' '));
            $this->signature = md5($this->api_secret . $sign_str);
            return $this->signature;
        }
        else
        {
            return false;
        }
    }

    /**
    *   IssuuServiceAPI::validFieldJson()
    *
    *   Valida uma variável
    *
    *   @access protected
    *   @param object $object
    *   @param string $field Nome da variável a ser validada
    *   @param int $type Corresponde ao tipo que a variável será convertida
    *   @return string Retorna a variável validada ou uma string vazia caso ela não exista
    */
    protected function validFieldJson($object, $field, $type = 0)
    {
        if (isset($object->$field))
        {
            if ($type == 0)
            {
                return (string) $object->$field;
            }
            else if ($type == 1)
            {
                return (int) $object->$field;
            }
            else if ($type == 2)
            {
                return (is_bool($object->$field))? $object->$field : (($object->$field == 'true')? true : false);
            }
            else
            {
                return $object->$field;
            }
        }
        else
        {
            return '';
        }
    }

    /**
    *   IssuuServiceAPI::validFieldXML()
    *
    *   Valida uma variável
    *
    *   @access protected
    *   @param array $object
    *   @param string $field Nome da variável a ser validada
    *   @param int $type Corresponde ao tipo que a variável será convertida
    *   @return string Retorna a variável validada ou uma string vazia caso ela não exista
    */
    protected function validFieldXML($object, $field, $type = 0)
    {
        if (isset($object[$field]))
        {
            if ($type == 0)
            {
                return (string) $object[$field];
            }
            else if ($type == 1)
            {
                return (int) $object[$field];
            }
            else if ($type == 2)
            {
                return (is_bool($object[$field]))? $object[$field] : (($object[$field] == 'true')? true : false);
            }
            else
            {
                return $object[$field];
            }
        }
        else
        {
            return '';
        }
    }

    /**
    *   IssuuServiceAPI::returnErrorJson()
    *
    *   Retorna objeto de erro
    *
    *   @access protected
    *   @param object $response Correspondente ao objeto de resposta da requisição
    *   @return array Array contendo o conteúdo do erro
    */
    protected function returnErrorJson($response)
    {
        return array(
            'stat' => 'fail',
            'code' => $response->_content->error->code,
            'message' => $response->_content->error->message,
            'field' => $response->_content->error->field
        );
    }

    /**
    *   IssuuServiceAPI::returnErrorXML()
    *
    *   Retorna objeto do erros
    *
    *   @access protected
    *   @param object $response Correspondente ao objeto de resposta da requisição
    *   @return array Array contendo o conteúdo do erro
    */
    protected function returnErrorXML($response)
    {
        return array(
            'stat' => 'fail',
            'code' => (string) $response->error['code'],
            'message' => (string) $response->error['message'],
            'field' => (string) $response->error['field']
        );
    }

    /**
    *   IssuuServiceAPI::returnSingleResult()
    *
    *   Faz a requisição de um único documento.
    *
    *   @access protected
    *   @param array $params Correspondente aos parâmetros da requisição
    *   @return array Retorna um array com a resposta da requisição
    */
    final protected function returnSingleResult($params)
    {
        $this->setParams($params);

        $curl = curl_init($this->buildUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        $slug = $this->slug_section;

        if (isset($params['format']) && $params['format'] == 'json')
        {
            $response = json_decode($response);
            $response = $response->rsp;

            if($response->stat == 'ok')
            {
                $result['stat'] = 'ok';
                $result[$slug] = $this->clearObjectJson($response->_content->$slug);

                return $result;
            }
            else
            {
                return $this->returnErrorJson($response);
            }
        }
        else
        {
            $response = new SimpleXMLElement($response);

            if ($response['stat'] == 'ok')
            {
                $result['stat'] = 'ok';
                $result[$slug] = $this->clearObjectXML($response->$slug);

                return $result;
            }
            else
            {
                return $this->returnErrorXML($response);
            }
        }
    }

    /**
    *   IssuuServiceAPI::delete()
    *
    *   Exclui os registros da requisição
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    */
    final public function delete($params = array())
    {
        $params['action'] = $this->delete;
        $this->setParams($params);

        $curl = curl_init($this->buildUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        if (isset($params['format']) && $params['format'] == 'json')
        {
            $response = json_decode($response);
            $response = $response->rsp;

            if ($response->stat == 'ok')
            {
                return array('stat' => 'ok');
            }
            else
            {
                return $this->returnErrorJson($response);
            }
        }
        else
        {
            $response = new SimpleXMLElement($response);

            if ($response['stat'] == 'ok')
            {
                return array('stat' => 'ok');
            }
            else
            {
                return $this->returnErrorXML($response);
            }
        }
    }

    /**
    *   IssuuServiceAPI::issuuList()
    *
    *   Lista registros da requisição
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    */
    final public function issuuList($params = array())
    {
        $params['action'] = $this->list;
        $this->setParams($params);

        $curl = curl_init($this->buildUrl());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        $slug = $this->slug_section;

        if (isset($params['format']) && $params['format'] == 'json')
        {
            $response = json_decode($response);
            $response = $response->rsp;

            if ($response->stat == 'ok')
            {
                $result['stat'] = 'ok';
                $result['totalCount'] = (int) $response->_content->result->totalCount;
                $result['startIndex'] = (int) $response->_content->result->startIndex;
                $result['pageSize'] = (int) $response->_content->result->pageSize;
                $result['more'] = (is_bool($response->_content->result->more))? $response->_content->result->more :
                    (((string) $response->_content->result->more == 'true')? true : false);

                if (!empty($response->_content->result->_content))
                {
                    foreach ($response->_content->result->_content as $item) {
                        $item = $item->$slug;
                        $result[$slug][] = $this->clearObjectJson($item);
                    }
                }

                return $result;
            }
            else
            {
                return $this->returnErrorJson($response);
            }
        }
        else
        {
            $response = new SimpleXMLElement($response);

            if ($response['stat'] == 'ok')
            {
                $result['stat'] = 'ok';
                $result['totalCount'] = (int) $response->result['totalCount'];
                $result['startIndex'] = (int) $response->result['startIndex'];
                $result['pageSize'] = (int) $response->result['pageSize'];
                $result['more'] = (is_bool($response->result['more']))? $response->result['more'] :
                    (((string) $response->result['more'] == 'true')? true : false);

                if ($response->result->$slug)
                {
                    $result[$slug] = array();
                    foreach ($response->result->$slug as $item) {
                        $result[$slug][] = $this->clearObjectXML($item);
                    }
                }

                return $result;
            }
            else
            {
                return $this->returnErrorXML($response);
            }
        }
    }

    /**
    *   IssuuServiceAPI::clearObjectXML()
    *
    *   Valida os atributos de um objeto XML
    *
    *   @access protected
    *   @param object $object Correspondente ao objeto XML a ser validado
    */
    abstract protected function clearObjectXML($object);

    /**
    *   IssuuServiceAPI::clearObjectJson()
    *
    *   Valida os atributos de um objeto Json
    *
    *   @access protected
    *   @param object $object Correspondente ao objeto Json a ser validado
    */
    abstract protected function clearObjectJson($object);
}