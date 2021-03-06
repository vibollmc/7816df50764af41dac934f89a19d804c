﻿using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace HighLights.Web.Entities
{
    public class Category : Base
    {
        [Required, MaxLength(50)]
        public string Name { get; set; }
        [Required, MaxLength(50)]
        public string Slug { get; set; }
        public bool IsMenu { get; set; }

        public virtual ICollection<Match> Matches { get; set; }
    }
}
