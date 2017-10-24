using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace YTCommenter.Models
{
    public class GoogleApi
    {
        public int? Id { get; set; }
        public string Name { get; set; }
        public string ApiKey { get; set; }
        public string ClientId { get; set; }
        public string ClientSecret { get; set; }
        public string FileName { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? AuthorizedAt { get; set; }
        public DateTime? LastUsed { get; set; }
    }
}
