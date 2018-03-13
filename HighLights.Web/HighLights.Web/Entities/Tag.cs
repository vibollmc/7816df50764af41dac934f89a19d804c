using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class Tag : Base
    {
        [Required, MaxLength(50)]
        public string Name { get; set; }
        [Required, MaxLength(50)]
        public string Slug { get; set; }

        public ICollection<TagAssignment> TagAssignments { get; set; }
    }
}
