<?php
class PBFloat extends PBScalar
{
    var $wired_type = PBMessage::WIRED_32BIT;

    /**
     * Parses the message for this type
     *
     * @param array
     */
    public function ParseFromArray()
    {
      $str = '';
      for( $i=0; $i<4; $i++ )
      {
        $n = $this->reader->next();
        $str .= chr($n);
      }
      $u = unpack( 'fval', $str );
      $this->value = $u['val'];
    }

    /**
     * Serializes type
     */
   public function SerializeToString($rec=-1)
   {
        $string = '';

        if ($rec > -1)
        {
          $string .= $this->base128->set_value($rec << 3 | $this->wired_type);
        }

        $p = pack( 'f', $this->value );
        for( $i=0; $i<4; $i++ )
        {
          $string .= $this->base128->set_value( ord($p{$i}) );
        }
        return $string;
   }
}
?>
