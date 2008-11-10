<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

class Model_User extends Model
{
   public function Model_User()
   {
      parent::Model();
   }

   public function LoginUserData($login)
   {
      $login = mysql_real_escape_string($login);

      return $this->db->query("SELECT `password`, `hashalgo`, `salt`, `id` FROM `__users` ".
                              "WHERE `nick` = '%1'", $login);
   }
   
   public function UpdatePassword($nick, $hash, $salt, $hashAlgo)
   {
      return $this->db->query("UPDATE  `__users` SET " .
                              "`password` = '%1', `hashalgo` = '%2', `salt` = '%3' " .
                              "WHERE `nick` = '%4'", $hash, $hashAlgo, $salt, $nick);
   }
   
   public function UserExists($nick)
   {
      $user = $this->db->query("SELECT `id` FROM `__users` WHERE `nick` = '%1'", $nick);
      $user = $user->num_rows();
      return ($user == 0 ? FALSE : TRUE);
   }
}

?>