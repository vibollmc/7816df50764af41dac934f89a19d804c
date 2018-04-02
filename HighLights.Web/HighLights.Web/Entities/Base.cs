using System;
using System.ComponentModel.DataAnnotations;

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
