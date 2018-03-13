using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Entities
{
    public class Match : Base
    {
        [Required, MaxLength(500)]
        public string Title { get; set; }
        [Required, MaxLength(500)]
        public string Slug { get; set; }
        [Required]
        public DateTime? MatchDate { get; set; }
        
        public decimal? CategoryId { get; set; }
        public Category Category { get; set; }

        public ICollection<Clip> Clips { get; set; }
        public ICollection<Formation> Formations { get; set; }
        public ICollection<TagAssignment> TagAssignments { get; set; }
    }
}
