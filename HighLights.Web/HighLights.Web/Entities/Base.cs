using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class Base
    {
        public Base()
        {
            CreatedAt = DateTime.UtcNow;
        }
        [Key]
        public int? Id { get; set; }

        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
    }
}
