using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.ViewModels;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace HighLights.Web.Pages
{
    public class MatchModel : PageModel
    {
        private readonly IMatchRepository _matchRepository;

        public MatchModel(IMatchRepository matchRepository)
        {
            _matchRepository = matchRepository;
        }

        [BindProperty]
        public MatchDetail Match { get; set; }

        public async Task<IActionResult> OnGetAsync(string slug)
        {
            Match = await _matchRepository.GetMatchDetail(slug);

            if (Match == null) return NotFound();

            return Page();
        }
    }
}