using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Entities;
using HighLights.Web.Utilities;
using HighLights.Web.Utilities.Model;
using Microsoft.CodeAnalysis.CSharp;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web.Dal.Implement
{
    public class MatchRepository : IMatchRepository
    {
        private readonly HighLightsContext _dbContext;

        public MatchRepository(HighLightsContext dbContext)
        {
            _dbContext = dbContext;
        }

        public async Task<bool> CheckExsits(string slug)
        {
            return await _dbContext.Matchs.AnyAsync(x => x.Slug == slug);
        }

        public async Task<bool> Add(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions)
        {
            if (match == null || clips == null) return false;

            using (var dbTransaction = _dbContext.Database.BeginTransaction())
            {
                try
                {
                    //save category
                    var category = await _dbContext.Categories.FirstOrDefaultAsync(x => x.Name.Equals(match.Competition, StringComparison.OrdinalIgnoreCase));

                    if (category == null)
                    {
                        category = new Category
                        {
                            Name = match.Competition,
                            Slug = match.Competition.Replace(" ", "-").ToLower(),
                            IsMenu = false
                        };

                        await _dbContext.Categories.AddAsync(category);
                        await _dbContext.SaveChangesAsync();
                    }

                    //Save match
                    match.CategoryId = category.Id;

                    await _dbContext.Matchs.AddAsync(match);
                    await _dbContext.SaveChangesAsync();

                    //save clip
                    foreach (var clip in clips)
                    {
                        clip.MatchId = match.Id;
                    }

                    await _dbContext.Clips.AddRangeAsync(clips);
                    await _dbContext.SaveChangesAsync();

                    //save formation
                    if (formations != null)
                    {
                        var formationsAdded = formations.Where(x => !x.IsSubstitution).Select(x => new Formation
                        {
                            Name = x.Name,
                            Type = x.Type,
                            Number = x.Number,
                            MatchId = match.Id,
                            IsSubstitution = actionSubstitutions?.Any(y=>y.Out.Equals(x.Name, StringComparison.OrdinalIgnoreCase)) ?? false
                        });
                        await _dbContext.Formations.AddRangeAsync(formationsAdded);
                        await _dbContext.SaveChangesAsync();

                        //save substitution
                        if (substitutions != null)
                        {
                            foreach (var substitution in substitutions)
                            {
                                substitution.MatchId = match.Id;
                                var actionSubs = actionSubstitutions?.FirstOrDefault(x =>
                                    x.In.Equals(substitution.Name, StringComparison.OrdinalIgnoreCase));

                                if (actionSubs == null) continue;
                                
                                substitution.Minutes = actionSubs.Min.ToInt();
                                var formation = formationsAdded.FirstOrDefault(x =>
                                    x.Name.Equals(actionSubs.Out, StringComparison.OrdinalIgnoreCase));

                                substitution.FormationId = formation?.Id;
                            }

                            await _dbContext.Substitutions.AddRangeAsync(substitutions);
                            await _dbContext.SaveChangesAsync();
                        }
                    }

                    //Save tag 1
                    var tag = await _dbContext.Tags.FirstOrDefaultAsync(x =>
                        x.Name.Equals(match.Home, StringComparison.OrdinalIgnoreCase));
                    if (tag == null)
                    {
                        tag = new Tag
                        {
                            Name = match.Home,
                            Slug = match.Home.Replace(" ", "").ToLower()
                        };
                        await _dbContext.Tags.AddAsync(tag);
                        await _dbContext.SaveChangesAsync();
                    }

                    await _dbContext.TagAssignments.AddAsync(new TagAssignment
                    {
                        TagId = tag.Id.Value,
                        MatchId = match.Id.Value
                    });
                    await _dbContext.SaveChangesAsync();

                    //Save tag 2
                    tag = await _dbContext.Tags.FirstOrDefaultAsync(x =>
                        x.Name.Equals(match.Away, StringComparison.OrdinalIgnoreCase));
                    if (tag == null)
                    {
                        tag = new Tag
                        {
                            Name = match.Away,
                            Slug = match.Away.Replace(" ", "").ToLower()
                        };
                        await _dbContext.Tags.AddAsync(tag);
                        await _dbContext.SaveChangesAsync();
                    }

                    await _dbContext.TagAssignments.AddAsync(new TagAssignment
                    {
                        TagId = tag.Id.Value,
                        MatchId = match.Id.Value
                    });
                    await _dbContext.SaveChangesAsync();

                    dbTransaction.Commit();
                }
                catch (Exception ex)
                {
                    dbTransaction.Rollback();

                    Console.WriteLine(ex.Message);
                }
            }

            return true;
        }
    }
}
