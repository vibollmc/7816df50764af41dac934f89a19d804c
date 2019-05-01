using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.ViewModels;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace Football.Show.Pages
{
    public class MatchModel : PageModel
    {
        private readonly IMatchRepository _matchRepository;

        public MatchModel(IMatchRepository matchRepository)
        {
            _matchRepository = matchRepository;
        }

        public MatchDetail MatchDetail;
        public IEnumerable<Match> MatchNewests;

        public async Task OnGetAsync(string slug)
        {
            MatchDetail = await _matchRepository.GetMatchDetail(slug);

            MatchNewests = await _matchRepository.GetMatchsNewest(MatchDetail.Id);
        }
    }
}