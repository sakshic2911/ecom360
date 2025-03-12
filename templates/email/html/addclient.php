<!DOCTYPE html>
	                  <html>
	                  <head></head>
	                  <body>
	                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
	                      <tr>
	                        <td align=center>
	                          <img src="<?= ECOM360 ?>/img/ECOM360/Ecom360logo.png" style="height: 67px;">
	                          <hr>
	                          
	                        </td>
	                      </tr>
	                      
	                      <tr>
	                        <td style="font-size: 14px;">
	                          <p style="word-spacing: 3px;">Hey, <?= $name;?>! </p>
	                          <p style="word-spacing: 3px;">Welcome to the Ecom 360 Portal!</p>
                              <p style="word-spacing: 3px;">Ultimately, this is the only login you will need to buy inventory, see your store's sales, chat with support, and view your reconciled monthly statements.</p>
							  <p style="word-spacing: 3px;"><strong>Username:</strong> <?= $email;?><br>
                                <strong>Password:</strong> <?= $password;?></p>
                                <p style="word-spacing: 3px;"><a href="<?= ECOM360 ?>/">Click here</a> to log in.
                                </p>

	                         
	                          <p style="word-spacing: 3px;">Thank you!
<br>Ecom 360</p>
	                        </td>
	                      </tr>
	                    </table>
	                    
	                  </body>
	                  </html>