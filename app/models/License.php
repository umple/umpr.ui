<?php

namespace cruise\umple\umpr\ui;

/**
 * Hold information about the license of repository. This includes the License's {@link URL} for linking.
 *
 * @author Kevin Brightwell <kevin.brightwell2@gmail.com>
 *
 * @since Apr 9, 2015
 */
class License {
  
  public static $CC_ATTRIBUTION_4 = "http://creativecommons.org/licenses/by/4.0/";
  
  public static $EPL = "https://www.eclipse.org/legal/epl-v10.html";
  
  public static $W3C = "http://www.w3.org/Consortium/Legal/2015/doc-license";
  
  public static $MIT = "http://opensource.org/licenses/MIT";
  
  /**
   * Signifies that the License is unknown. 
   */
  public static $UNKNOWN = "";
  
}