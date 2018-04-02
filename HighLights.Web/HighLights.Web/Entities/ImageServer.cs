using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace HighLights.Web.Entities
{
    public class ImageServer : Base
    {
        [Required, MaxLength(50)]
        public string ServerFtp { get; set; }
        [Required, MaxLength(50)]
        public string UserName { get; set; }
        [Required, MaxLength(50)]
        public string Password { get; set; }
        [Required, MaxLength(50)]
        public string Patch { get; set; }
        [Required, MaxLength(50)]
        public string ServerUrl { get; set; }
        [Required]
        public int Port { get; set; }
        [Required, MaxLength(50)]
        public string ServerName { get; set; }
        
        public ICollection<Match> Matches { get; set; }
    }
}
