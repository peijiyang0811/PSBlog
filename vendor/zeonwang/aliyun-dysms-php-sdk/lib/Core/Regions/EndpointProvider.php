<?php

namespace Aliyun\Core\Regions;

class EndpointProvider
{
	private static $endpoints;
	
	public static function findProductDomain($regionId, $product)
	{
		if(null == $regionId || null == $product || null == self::$endpoints)
		{
			return null;
		}
		
		foreach (self::$endpoints as $key => $endpoint)
		{

            /*
                // $endpoint  => object(Aliyun\Core\Regions\Endpoint)
                object(Aliyun\Core\Regions\Endpoint)#2393 (3) {
                    ["name":"Aliyun\Core\Regions\Endpoint":private]=>
                    string(11) "cn-hangzhou"
                    ["regionIds":"Aliyun\Core\Regions\Endpoint":private]=>
                    array(1) {
                      [0]=>
                      string(11) "cn-hangzhou"
                    }
                    ["productDomains":"Aliyun\Core\Regions\Endpoint":private]=>
                    array(78) {
                      [77]=>
                      object(Aliyun\Core\Regions\ProductDomain)#1771 (2) {
                        ["productName":"Aliyun\Core\Regions\ProductDomain":private]=>
                        string(8) "Dysmsapi"
                        ["domainName":"Aliyun\Core\Regions\ProductDomain":private]=>
                        string(21) "dysmsapi.aliyuncs.com"
                      }
                    }
                  }
                 $regionId  => string(21) "dysmsapi.aliyuncs.com"
                 $endpoint->getRegionIds() =>       array(1) {"cn-hangzhou" }

            $product => "string(8) "Dysmsapi"

                 $endpoint->getProductDomains   => array(78) {
                        [77]=>
                          object(Aliyun\Core\Regions\ProductDomain)#1771 (2) {
                            ["productName":"Aliyun\Core\Regions\ProductDomain":private]=>
                            string(8) "Dysmsapi"
                            ["domainName":"Aliyun\Core\Regions\ProductDomain":private]=>
                            string(21) "dysmsapi.aliyuncs.com"
                          }
                        }
             * */
            if(in_array($regionId, $endpoint->getRegionIds()))
			{
			 	return self::findProductDomainByProduct($endpoint->getProductDomains(), $product);
			}	
		}
		return null;
	}
	
	private static function findProductDomainByProduct($productDomains, $product)
	{
		if(null == $productDomains)
		{
			return null;
		}
		foreach ($productDomains as $key => $productDomain)
		{
			if($product == $productDomain->getProductName())
			{
				return $productDomain->getDomainName();
			}
		}
		return null;
	}
	
	
	public static function getEndpoints()
	{
		return self::$endpoints;
	}
	
	public static function setEndpoints($endpoints)
	{
		self::$endpoints = $endpoints;
	}
	
}