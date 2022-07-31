
 <?php

  function persentPrice($price)
   {
        return sprintf('$%01.2f', $price / 100);
    }
    function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}